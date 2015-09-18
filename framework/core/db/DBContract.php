<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 16/09/15
 * Time: 6:22 PM
 */
namespace Lp\Framework\Core\Db;

Interface DBContract
{
    public function insert($sql, $param = array(), $extra = array());
    public function update($sql, $param = array(), $extra = array());
    public function delete($sql, $param = array(), $extra = array());
    public function batch($sql, $param = array(), $extra = array());
}