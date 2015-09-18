<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 20/08/15
 * Time: 5:10 AM
 */

/**
 *  Bootstrap the application
 */


include dirname(__DIR__)."/framework/bootstrap.php";
(new ClassLoader(BASE_PATH))->register();

//$conn = Lp\Framework\Core\Db\DbSingleton::getConnectionFromPool();
//var_dump($conn);
//
//
//$conn = Lp\Framework\Core\Db\DbSingleton::getConnectionFromPool();
//var_dump($conn);
//
//$sql = "SELECT * FROM NewTable";
//var_dump((new \Lp\Framework\Core\Db\DBLayer())->insert($sql));
//exit();


Lp\Framework\Core\Request\Request::cleanTheGlobals();
var_dump( APPLICATION_PATH."routes/routes.php");
include APPLICATION_PATH."routes/routes.php";
(new Lp\Framework\Core\Optimus())->letsRoll();