<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 16/09/15
 * Time: 8:22 PM
 */

namespace Lp\Framework\Core\Db;

use \PDO as PDO;
class DBLayer implements DBContract
{
    private $connection;

    private function getConnection()
    {
        if (null == $this->connection || !($this->connection instanceof PDO)){
            $this->connection = DbSingleton::getConnectionFromPool();
        }
        return $this->connection;
    }

    public function insert($sql, $param = array(), $extra = array())
    {
        $conn = $this->getConnection();
        $preparedStmt = $conn->prepare($sql);
        foreach($param as $key=>$value) {
            $keyInSql = ":".$key;
            if (strpos($sql, $keyInSql) !== false) {
                $preparedStmt->bindParam($keyInSql, $value);
            } else {
                throw new \Exception("Illegal bind params supplied");
            }
        }
        $preparedStmt->execute();
        return $preparedStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($sql, $param = array(), $extra = array())
    {
        // TODO: Implement update() method.
    }

    public function delete($sql, $param = array(), $extra = array())
    {
        // TODO: Implement delete() method.
    }

    public function batch($sql, $param = array(), $extra = array())
    {
        // TODO: Implement batch() method.
    }

}