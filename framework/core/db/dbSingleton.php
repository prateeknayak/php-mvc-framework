<?php
namespace Lp\Framework\Core\Db;

use Lp\Framework\Core\Db\Mysql\MysqlConnection;
use Lp\Framework\Exceptions\MaxConnectionReached;

/**
 * Singleton which creates a connection pool and
 * provides connection objects.
 *
 * Class DbSingleton
 * @author Prateek Nayak <prateek.1708@gmail.com>
 * @package Lp\Framework\Core\Db
 */
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
    private $connectionPool = array();

    /**
     * Static single instance of the class
     * @var self.
     */
    private static $_instance;

    /**
     * Obtain connection from pool if not empty
     * else create a new connection add it to the pool
     *
     * @return PDO connection
     * @throws MaxConnectionReached
     * @throws \Exception
     */

    public function getConnectionFromPool()
    {
        foreach($this->connectionPool as $index => $conn) {
            if ($this->checkConnection($conn)) {
                return $conn;
            }
        }
        if (count($this->connectionPool) < self::MAX_POOL_SIZE ) {
            $this->init();
            return $this->getConnectionFromPool();
        } else {
            throw new MaxConnectionReached("Unable to create new connection");
        }
    }

    /**
     * Checks the status of the connection.
     * @param $connection object to verify
     * @return mixed status
     *
     */
    private function checkConnection($connection)
    {
        return $connection->getAttribute(PDO::ATTR_CONNECTION_STATUS);
    }

    /**
     * Initialise connection to database.
     * Supports only mysql for now.
     */
    private function init()
    {
        $this->connectionPool[] = (new MysqlConnection(DB_HOST, DB_USER, DB_PASS, DB_CMS))->getInstance();
    }

    /**
     * Standard get instance for singleton class.
     * @return DbSingleton
     */
    public static function getInstance()
    {
        if (null == self::$_instance || !(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
