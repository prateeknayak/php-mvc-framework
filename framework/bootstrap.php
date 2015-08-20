<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 20/08/15
 * Time: 5:12 AM
 */


/**
 * Get the environment variables for now
 * Create a config loader class
 */
$basePath = dirname(__DIR__)."/";
$host = getenv("DB_HOSTNAME");
$user = getenv("DB_USER");
$pass = getenv("DB_PASS");
$cms  = getenv("DB_CMS");
$user = getenv("DB_USER");
$env  = getenv("ENVIRONMENT");
$envDir = getenv("ENVIRONMENT_DIR");

if (is_null($env) ||false == $env){
    $env = "local";
    $envDir ='lp';
}
define("ENVIRONMENT_DIR",$envDir);
include $basePath."framework/vendor/autoload.php";
include $basePath."framework/autoload/classLoader.php";

(new ClassLoader($basePath))->register();
Lp\Framework\Core\Request::cleanTheGlobals();
(new Lp\Framework\Core\lp())->letsRoll();
