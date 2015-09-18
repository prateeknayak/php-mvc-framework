<?php
namespace Lp\Framework\Core\Db;

use Lp\Framework\Exceptions\MaxConnectionReached;
use \PDO as PDO;
use \PDOException as PDOException;

class DbSingleton
{
    /**
     *  Maximum size of the connection pool
     *  limited to three since we are not persistent
     */
    const MAX_POOL_SIZE = 3;
    /**
     * Cp maintained through one request cycle
     */
    private static $connectionPool = array();

    /**
     * Obtain connection from pool if not empty
     * else create a new connection add it to the pool
     *
     * @return PDO connection
     * @throws MaxConnectionReached
     * @throws \Exception
     */

    public static function getConnectionFromPool()
    {
        foreach(self::$connectionPool as $index => $conn) {
            if (self::checkConnection($conn)) {
                echo "from pool";
                return $conn;
            }
        }
        if (count(self::$connectionPool) < self::MAX_POOL_SIZE ) {
            self::init(DB_HOST, DB_USER, DB_PASS, DB_CMS);
            echo "from start";
            return self::getConnectionFromPool();
        } else {
            throw new MaxConnectionReached("Unable to create new connection");
        }
    }

    /**
     *  Checks the status of the connection.
     * @param $connection object to verify
     * @return mixed status
     *
     */
    private static function checkConnection($connection)
    {
        return $connection->getAttribute(PDO::ATTR_CONNECTION_STATUS);
    }

    /**
     * Initialise connection to database.
     *
     * @param $host
     * @param $username
     * @param $password
     * @param $dbName
     * @param bool|false $emulate
     * @throws \Exception
     */
    private static function init($host, $username, $password, $dbName, $emulate = false) 
    {
        try {
            $instance = null;
            $hostArray = explode(":", $host);
			$dsn = 'mysql:dbname='.$dbName.';host='.$hostArray[0].'';
			if( (count($hostArray) > 1) && isset($hostArray[1]) && $hostArray[1] != ""){
				$dsn .= ';port='.$hostArray[1].'';
			}
		    $instance = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		    $instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    $instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		    $instance->setAttribute(PDO::ATTR_EMULATE_PREPARES, $emulate);

		} catch (PDOException $e) {
			throw new \Exception('Connection error: ' . $e->getMessage());
		}

        self::$connectionPool[] = $instance;
    }
}
