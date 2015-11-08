<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 16/09/15
 * Time: 8:22 PM
 */

namespace Lp\Framework\Core\Db;

use \PDO as Connection;
use \PDOStatement as Statement;

class DBLayer implements DBContract
{
    /**
     * Connection object
     * @var
     */
    private $connection;

    /**
     * Checks if there is a existing connection.
     * Or else returns one from pool.
     *
     * @return Connection
     * @throws \Lp\Framework\Exceptions\MaxConnectionReached
     * @throws \Exception
     */
    private function getConnection()
    {
        if (null == $this->connection || !($this->connection instanceof Connection)) {
            try {

                $this->connection = DbSingleton::getInstance()->getConnectionFromPool();
            } catch (\Exception $e) {
                throw $e;
            }
        }
        return $this->connection;
    }

    private function prepareAndBindParams(Connection $conn, $sql, $param)
    {
        $preparedStmt = $conn->prepare($sql);
        foreach($param as $key=>$value) {
            $keyInSql = ":".$key;
            if (strpos($sql, $keyInSql) !== false) {
                $preparedStmt->bindParam($keyInSql, $value);
            } else {
                throw new \Exception("Illegal bind params supplied");
            }
        }
        return $preparedStmt;
    }

    private function call($sql, $param = array(), $extra = array())
    {
        $conn = $this->getConnection();
        return $this->executeWithExceptionHandling($this->prepareAndBindParams($conn, $sql, $param), $conn);
    }

    public function select($sql, $param = array(), $extra = array())
    {
        $resultset = $this->call($sql, $param, $extra);
        return $resultset->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function executeWithExceptionHandling(Statement $preparedStmt, \PDO $conn)
    {
        try {
            $preparedStmt->execute();
            $conn->commit();
            return $preparedStmt;
        } catch(\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    public function insert($sql, $param = array(), $extra = array())
    {
        $this->call($sql, $param, $extra);

    }

    public function update($sql, $param = array(), $extra = array())
    {
        $this->call($sql, $param, $extra);
    }

    public function delete($sql, $param = array(), $extra = array())
    {
        $this->call($sql, $param, $extra);
    }

    public function batch($sql, $param = array(), $extra = array())
    {
        // TODO: Implement batch() method.
    }
}