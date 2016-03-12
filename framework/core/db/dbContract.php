<?php

namespace Wbd\Framework\Core\Db;

/**
 * Any dbLayer class which provides support to application
 * need to implement this interface.
 *
 * Interface DBContract
 * @author Prateek Nayak <prateek.1708@gmail.com>
 * @package Framework\Core\Db
 */
Interface DBContract
{
    /**
     * @param $sql
     * @param array $param
     * @param array $extra
     * @return mixed
     */
    public function select($sql, $param = array(), $extra = array());

    /**
     * @param $sql
     * @param array $param
     * @param array $extra
     * @return mixed
     */
    public function insert($sql, $param = array(), $extra = array());

    /**
     * @param $sql
     * @param array $param
     * @param array $extra
     * @return mixed
     */
    public function update($sql, $param = array(), $extra = array());

    /**
     * @param $sql
     * @param array $param
     * @param array $extra
     * @return mixed
     */
    public function delete($sql, $param = array(), $extra = array());

    /**
     * @param $sql
     * @param array $param
     * @param array $extra
     * @return mixed
     */
    public function batch($sql, $param = array(), $extra = array());
}