<?php
/**
 * Creates connections to the mysql db
 *
 * @author Prateek Nayak <prateek.1708@gmail.com>
 * @package Framework\Core\Db\Mysql
 *
 */

namespace Wbd\Framework\Core\Db\Mysql;

use \PDO as PDO;
use \PDOException as PDOException;

class MysqlConnection
{
    private $instance = null;

    public function __construct($host, $username, $password, $dbName, $emulate = false) {
        try {
            $hostArray = explode(":", $host);
            $dsn = 'mysql:dbname='.$dbName.';host='.$hostArray[0].'';
            if( (count($hostArray) > 1) && isset($hostArray[1]) && $hostArray[1] != ""){
                $dsn .= ';port='.$hostArray[1].'';
            }
            $this->instance = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $this->instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->instance->setAttribute(PDO::ATTR_EMULATE_PREPARES, $emulate);

        } catch (PDOException $e) {
            throw new \Exception('Connection error: ' . $e->getMessage());
        }
    }

    /**
     * @return null|PDO
     */
    public function getInstance()
    {
        return $this->instance;
    }


}