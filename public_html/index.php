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
Lp\Framework\Core\Request\Request::cleanTheGlobals();
include APPLICATION_PATH."routes/routes.php";
(new Lp\Framework\Core\Optimus())->letsRoll();
