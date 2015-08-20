<?php
namespace Healthand\Framework\Controller;

use \PDO as PDO;
use \PDOException as PDOException;
use \HTMLPurifier_Config as HTMLPurifier_Config;
use \HTMLPurifier as HTMLPurifier;
use Healthand\Framework\Controller\DbSingleton as DB;
use Healthand\Application\Lib\userAuditDetails as userDetails;
use Healthand\Framework\Exceptions\DbException as dbe;


class Database {
	
	private $pdo;
	private $sQuery;
	private $settings;
	private $bConnected = false;
	private $parameters = array();
	public $sort;
	public $order;
	public $limit = true;
	
	private $userDetails;
	
	
	public function __construct($host, $username, $password, $db, $dbType = 1, $emulate = false)
	{ 	
		if (2 === $dbType) {
			$ignite = "igniteUsers";
		} else if(3 === $dbType) {
			$ignite = "igniteSessions";
		} else {
			$ignite = "igniteCMS";
		}
		$this->pdo  = DB::$ignite($host, $username, $password, $db, $emulate = false);
		require_once LOCAL_SITE_PATH.'lib/HTMLPurifier/HTMLPurifier.auto.php';			
	}
	public function __destruct() 
	{
		DB::destroy();
		$this->pdo = null;
 	} 

		// public function CloseConnection(){
		// 	$this->pdo = null;
		// }
		
	
	private function Init($query, $parameters = "")
	{
		$config 			= HTMLPurifier_Config::createDefault();
		$HTMLPurifier 		= new HTMLPurifier($config);
		
		$statement = strtolower(substr($query, 0 , 6));
		
		if ( ($statement === 'insert' ||  $statement === 'update' ) && isset($parameters) && $parameters !== "") {
			foreach($parameters as $key => $value) {
				$parameters[$key] = $HTMLPurifier->purify($value);
			}
		}
		
		try {				
			@$this->sQuery = $this->pdo->prepare($query);
			
			$this->bindMore($parameters);

			if(!empty($this->parameters)) {
				foreach($this->parameters as $param) {
					$parameters = explode("\x7F", $param);
					if ( ($statement === 'insert' ||  $statement === 'update' ) && isset($parameters) && $parameters !== "") {
						foreach($parameters as $key => $value) {
							$parameters[$key] = $HTMLPurifier->purify($value);
						}
					}
							
					$this->sQuery->bindParam($parameters[0],$parameters[1]);
				}		
			} 
			
			$this->succes = $this->sQuery->execute();		
		
		}catch(PDOException $e){
			throw new dbe("Error Processing Request ".$e->getMessage());
		}

		// RESET THE PARAMETERS ARRAY
		$this->parameters = array();
	}
	
	// BIND THE PARAMETERS TOGETHER 
	public function bind($para, $value)
	{
		$this->parameters[sizeof($this->parameters)] = ":" . $para . "\x7F" . $value;
	}
	
	
    // BIND:ADD ALL THE PARAMETERS ARRAY
	public function bindMore($parray)
	{	
		if(empty($this->parameters) && is_array($parray)) {
			$columns = array_keys($parray);
			
			foreach($columns as $i => &$column) {
				$this->bind($column, $parray[$column]);
			}
		}
		
	}
	
	// AUDIT FIELDS
	public function getUserAuditDetails( $statementType )
	{
		 $AUDIT = new UserDetails;
		 
		 $TIME_STAMP = $AUDIT->getAuditTimestamp();
		 
		 $UserAuditDetails = $AUDIT->getUserAuditDetails();
		 
		 if( $statementType == 'insert' )
		 {
			$time_stamps = array( 'AUDT_CRTD_TS' => $TIME_STAMP, 'AUDT_MOD_TS' => $TIME_STAMP ); 
		 }
		 
		 if( $statementType == 'update' )
		 {
			$time_stamps = array( 'AUDT_MOD_TS' => $TIME_STAMP );
		 }
		 
		 return array_merge( array( 'AUDT_USR_MAP_ID' => $UserAuditDetails ), $time_stamps );
	}
	
    
	
    // QUERY METHOD		
	public function query($query, $params = null, $fetchmode = PDO::FETCH_ASSOC, $debug = false)
	{
		$query = trim($query);
		$this->Init($query, $params);
		$statement = strtolower(substr($query, 0 , 6));
		
		if($debug == true || $debug == 'true' || $debug == 'debug' || $debug == '1' || $debug == 1) {
			$this->pdoDebug($query, $params);
		}
		
		if ($statement === 'select') {
			return $this->sQuery->fetchAll($fetchmode);
		}
		elseif ( $statement === 'insert' ||  $statement === 'update' || $statement === 'delete' ) {
			return $this->sQuery->rowCount();
		}	
		else {
			return NULL;
		}
	}
	
   
    // GET THE LAST INSERTED ID	
	public function lastInsertId()
	{
		return $this->pdo->lastInsertId();
	}	
	
	
	// RETURN A COLUMN
	public function column($query,$params = null)
	{
		$this->Init($query,$params);
		$Columns = $this->sQuery->fetchAll(PDO::FETCH_NUM);		
		$column = null;

		foreach($Columns as $cells) {
			$column[] = $cells[0];
		}

		return $column;
		
	}	
   
	// RETURN A ROW
	public function row($query, $params = null, $fetchmode = PDO::FETCH_ASSOC, $debug = false)
	{				
		$this->Init($query,$params, $debug);
		if($debug == true || $debug == 'true' || $debug == 'debug' || $debug == '1' || $debug == 1) $this->pdoDebug($query, $params);
		return $this->sQuery->fetch($fetchmode);			
	}
	
    
	// RETURN A SINGLE FIELD OR COLUMN
	public function single($query,$params = NULL)
	{
		$this->Init($query,$params);
		return $this->sQuery->fetchColumn();
	}
	
	private function pdoDebug($query, $params)
	{
		echo '<pre>';
		$paramKeys = array_keys($params);
		foreach($paramKeys as $key){
			$pKey[] = "/:".$key."/";
			$pRep[] = "'".$params[$key]."'";
		}
		$strOut = preg_replace($pKey, $pRep, $query);
		echo $strOut;
		echo '</pre>';
	}
	
	public function countQuery($query, $filters = NULL, $debug = false) 
	{
		if(isset($filters['search'])) {
			if(strpos($query, " WHERE "))
				$query.= " AND (";
			else $query .= " WHERE (";
			$counter = 1;
			foreach($filters['searchField'] as $searchField) {
				$queryField = ":searchQuery".$counter;
				$query .= $searchField." LIKE concat('%', :searchQuery".$counter." , '%')";
				if($counter != count($filters['searchField']))
					$query .= "OR ";
				else $query .= ")";
				$counter ++;
			}
		}
		$this->sQuery = $this->pdo->prepare($query);
		if(isset($filters['search'])) {
			$counter = 1;
			foreach($filters['searchField'] as $searchField) {
				$this->sQuery->bindValue(":searchQuery".$counter, $filters['search'], PDO::PARAM_STR);
				$counter ++;
			}
		}
		$this->sQuery->execute();
		if($debug == true || $debug == 'true' || $debug == 'debug' || $debug == '1' || $debug == 1) $this->pdoDebug($query, $filters);
		return $this->sQuery->fetchColumn();
	}

	public function countQueryParams($query, $params, $debug = false) {
		$this->sQuery = $this->pdo->prepare($query);
		if(isset($params)) {
			$counter = 1;
			foreach($params as $key => $value) {
				$this->sQuery->bindValue($key, $value, PDO::PARAM_STR);
				$counter ++;
			}
		}
		$this->sQuery->execute();
		if($debug == true || $debug == 'true' || $debug == 'debug' || $debug == '1' || $debug == 1) $this->pdoDebug($query, $filters);
		return $this->sQuery->fetchColumn();
	}
		
	public function delete($table, $whereArray, $extraString = NULL) 
	{
		$counter = 1;
		$whereString = '';
		foreach($whereArray as $whereName => $whereValue) {
			$variable = 'v'.$counter;
			$whereString .= $whereName.' = :'.$variable;
			$pdoArrayValue[$variable] = $whereValue;
			if($counter < count($whereArray))
				$whereString .= " AND ";
			$counter ++;
		}
		$query	= $this->query("DELETE FROM ".$table." WHERE ".$whereString." ".$extraString, $pdoArrayValue);
		return $query;
	}

	public function GetSelectValues($table, $fieldValue, $displayName, $addSelectOption = false) 
	{
		$query = $this->query('SELECT '.$fieldValue.', '.$displayName.' FROM '.$table, NULL, PDO::FETCH_NUM);
		if($addSelectOption != false)
			array_unshift($query, array('', '---Select---'));
		return $query;
	}

	public function insert($table, $queryArray, $appID = NULL) 
	{
		$config = HTMLPurifier_Config::createDefault();
		$HTMLPurifier = new HTMLPurifier($config);
		$counter = 1;
		$pdoArrayValue = array();
		$fieldString = '';
		$valueString = '';
		
		if(!is_null($appID)) {	
			$queryArray = array_merge($queryArray,  $appID );
		}
		
		$queryArray = array_merge($queryArray,  $this->getUserAuditDetails( 'insert' ) );
		
		foreach($queryArray as $fieldName => $fieldValue) {
			$fieldString .= $HTMLPurifier->purify($fieldName);
			$valueString .= ":".$counter;
			$pdoArrayValue[$counter] = $HTMLPurifier->purify($fieldValue);
			if($counter < count($queryArray)) {
				$fieldString .= ", ";
				$valueString .= ", ";
			}
			$counter ++;
		}
		
		$query	= $this->query("INSERT INTO ".$table." (".$fieldString.") VALUES (".$valueString.")", $pdoArrayValue);

		return $this->lastInsertId(); 
	}

	public function overrideInsert($table, $queryArray)
	{
		$config = HTMLPurifier_Config::createDefault();
		$HTMLPurifier = new HTMLPurifier($config);
		$counter = 1;
		$pdoArrayValue = array();
		$fieldString = '';
		$valueString = '';
		foreach($queryArray as $fieldName => $fieldValue) {
			$fieldString .= $HTMLPurifier->purify($fieldName);
			$valueString .= ":".$counter;
			$pdoArrayValue[$counter] = $HTMLPurifier->purify($fieldValue);
			if($counter < count($queryArray)) {
				$fieldString .= ", ";
				$valueString .= ", ";
			}
			$counter ++;
		}
		$query	= $this->query("INSERT INTO ".$table." (".$fieldString.") VALUES (".$valueString.")", $pdoArrayValue);

		return $query; 
	}




	public function sortQuery($query, $filters, $debug = false) 
	{
		if(isset($filters['search'])) {
			if(strpos($query, " WHERE "))
				$query.= " AND (";
			else $query .= " WHERE (";
			$counter = 1;
			foreach($filters['searchField'] as $searchField) {
				$queryField = ":searchQuery".$counter;
				$query .= $searchField." LIKE concat('%', :searchQuery".$counter." , '%')";
				if($counter != count($filters['searchField']))
					$query .= "OR ";
				else $query .= ")";
				$counter ++;
			}
		}
		$query.= " ORDER BY ".$filters['sort']." ".$filters['order'];
		if($this->limit)
			$query .= " LIMIT :start, ".$filters['rows_per_page'];
		$this->sQuery = $this->pdo->prepare($query);
		if($this->limit)
			$this->sQuery->bindValue(":start", $filters['start']);
		if(isset($filters['search'])) {
			$counter = 1;
			foreach($filters['searchField'] as $searchField) {
				$this->sQuery->bindValue(":searchQuery".$counter, $filters['search'], PDO::PARAM_STR);
				$counter ++;
			}
		}
		$this->sQuery->execute();
		$this->order = NULL;
		$this->sort = NULL;
		if($debug == true || $debug == 'true' || $debug == 'debug' || $debug == '1' || $debug == 1) {
			$this->pdoDebug($query, $filters);
		}
		return $this->sQuery->fetchAll();
	}

	public function update($table, $queryArray, $whereArray = NULL, $extraString = NULL, $debug = NULL)
	{
		$config = HTMLPurifier_Config::createDefault();
		$HTMLPurifier = new HTMLPurifier($config);
		$counter = 1;
		$pdoArrayValue = array();
		$updateString = '';
		$whereString = '';
		
		if( is_array($extraString) && isset($extraString['AUDT_APP_ID']) ) {	
			$queryArray = array_merge($queryArray,  $extraString );
			$extraString = '';
		}
		
		$queryArray = array_merge($queryArray,  $this->getUserAuditDetails( 'update' ) );
		
		
		foreach($queryArray as $fieldName => $fieldValue) {
			$updateString .= $fieldName.' = :'.$counter;
			$pdoArrayValue[$counter] = $HTMLPurifier->purify($fieldValue);
			if($counter < count($queryArray))
				$updateString .= ", ";
			$counter ++;
		}
		
		
		
		if(is_array($whereArray)) {
			$whereString = " WHERE ";
			foreach($whereArray as $whereName => $whereValue) {
				$whereString .= $whereName.' = :'.$counter;
				$pdoArrayValue[$counter] = $HTMLPurifier->purify($whereValue);
				if($counter < count($queryArray) + count($whereArray))
					$whereString .= " AND ";
				$counter ++;
			}
		}
		
		$query	= $this->query("UPDATE ".$table." SET ". $updateString. $whereString. " ".$extraString, $pdoArrayValue);
		
		return $query;
	}

	public function sanitiseOutput($array) {
		/*if(is_array($array))
			foreach ($array as $key => $value)
				if (is_array($value))
					$array[$key] = $this->sanitiseOutput($value);
				else $array[$key] = htmlentities($value);
		else $array = htmlentities($array);*/
		return $array;
	}
	// METHODS FOR SESSION DB
	public function rollback() 
	{
		$this->pdo->rollback();
	}
	public function initTransactionStatements()
	{

		$sql = 'SET TRANSACTION ISOLATION LEVEL READ COMMITTED';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
		$this->pdo->beginTransaction();
	}	

	public function commitAndTerminateTransactionStatements()
	{
		try {
	        $this->pdo->commit();
			return true;
	    } catch (PDOException $e) {
	        $this->rollback();
	        return $e->getMessage();
	    }
	}
	public function lockTheTableAndGenerateReleaseLockKeyToAvoidDeadLock($idToLockTable) 
	{
	    $stmt = $this->pdo->prepare('SELECT GET_LOCK(:key, 50)');
	    $stmt->bindValue(':key', $idToLockTable, \PDO::PARAM_STR);
	    $stmt->execute();
	    $releaseStmt = $this->pdo->prepare('SELECT RELEASE_LOCK(:key)');
	    $releaseStmt->bindValue(':key', $idToLockTable, \PDO::PARAM_STR);

	    return $releaseStmt;
	}

	public function executeStatments($statement)
	{
		return $statement->execute();
	}


	public function batchInsertIntoOneTable($table="", $data = "")
	{	
		require_once LOCAL_SITE_PATH.'lib/HTMLPurifier/HTMLPurifier.auto.php';
		$config = HTMLPurifier_Config::createDefault();
		$HTMLPurifier = new HTMLPurifier($config);
	    $fieldString = '';
	    $sanitizedParams = array(); 
	    foreach ($data as $d) { 
	        $fieldString = '';
	        $counter = 1;
	        $sanitized = array();
	        foreach($d as $key => $value) {
	            $key 	= $HTMLPurifier->purify($key);
	            $pValue 	= $HTMLPurifier->purify($value);
	        	if(is_integer($value)){
	        		$pValue = intval($pValue);
	        	}
	            $sanitized[$key] = $pValue;
	            if($counter > 1 ) {
	                $fieldString .= ", ";
	            }
	            $fieldString .= $key;
	            $counter ++;
	        }
	        $sanitizedParams[] = $sanitized;
	    }
	    $table = $HTMLPurifier->purify($table);
	    $query = 'INSERT INTO '.$table.' ('.$fieldString.' ) VALUES '; 
	    $fieldStringArray = explode(",", $fieldString);
	    $binderCount = count($fieldStringArray);
	    $binder = '( ';
	        for($i = 0; $i<$binderCount;$i++){
	            $binder .="?";
	            if($binderCount > 1 && $i != ($binderCount -1) ) $binder .= ", ";
	        }
	    $binder .= ' )'; 
	    $qPart = array_fill(0, count($sanitizedParams), $binder);
	    $query .=  implode(",",$qPart);
	   	try {
	   		$stmt = $this->pdo->prepare($query);
	        $i = 1;
	        foreach($sanitizedParams as $item) { 
	            foreach ($fieldStringArray as $f) {
	                 $stmt -> bindParam($i++, $item[trim($f)]);
	            }
	        }
	       	$this->executeStatments($stmt);
	   	} catch (\PDOException $e) {
		      		return $e->getMessage();
	   	}

	   	return $this->lastInsertId();
    }	
}
?>