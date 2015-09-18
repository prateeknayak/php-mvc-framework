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

$host = "192.168.33.11:3306";//DB_HOSTNAME");
$user = "root";//getenv("DB_USER");
$pass = "password";//getenv("DB_PASS");
$cms  = "test";//getenv("DB_CMS");

if (is_null($env) ||false == $env){
    $env = "local";
    $envDir ='lp';
}

$confToLoad = $basePath."application/config/conf-".$env.".php";
if (file_exists($confToLoad)) {
    include $confToLoad;
    $confToLoad = null;
} else {
    echo json_encode(array("status"=>9191,"msg"=>"Missing conf file. Please contact admin."));
}

$siteConstantsFile = $application."application/config/siteConstants.php";
if (file_exists($siteConstantsFile)) {
    include $siteConstantsFile;
    $siteConstantsFile = null;
} else {
    echo json_encode(array("status"=>9191,"msg"=>"Missing constants file. Please contact admin."));
}
include BASE_PATH."vendor/autoload.php";
include FRAMEWORK_PATH."autoload/classLoader.php";
