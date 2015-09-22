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
 *  // TODO etcd to serve env.
 */
$basePath = dirname(__DIR__)."/";
$host = getenv("DB_HOSTNAME");
$user = getenv("DB_USER");
$pass = getenv("DB_PASS");
$cms  = getenv("DB_CMS");
$user = getenv("DB_USER");
$env  = getenv("ENVIRONMENT");
$envDir = getenv("ENVIRONMENT_DIR");


/**
 *  check if env is set or not
 *  if not then its local
 */
if (is_null($env) ||false == $env){
    $env = "local";
    $envDir ='lp';

    /**
     *  overriding db params for testing
     */
    $host = "192.168.33.11:3306";//DB_HOSTNAME");
    $user = "root";//getenv("DB_USER");
    $pass = "password";//getenv("DB_PASS");
    $cms  = "test";//getenv("DB_CMS");
}

/**
 *  Lets load the application conf based on env
 */
$confToLoad = $basePath."application/config/config-".$env.".php";
if (file_exists($confToLoad)) {
    include $confToLoad;
    $confToLoad = null;
} else {
    echo json_encode(array("status"=>9191,"msg"=>"Missing conf file. Please contact admin."));
    exit();
}

/**
 *  Loading the site constants. Used across application.
 */
$siteConstantsFile = $application."config/siteConstants.php";
if (file_exists($siteConstantsFile)) {
    include $siteConstantsFile;
    $siteConstantsFile = null;
} else {
    echo json_encode(array("status"=>9191,"msg"=>"Missing constants file. Please contact admin."));
    exit();
}
/**
 *  Kick start the framework and vendors.
 *  vroom vroooom off we go.
 */
include BASE_PATH."vendor/autoload.php";
include FRAMEWORK_PATH."autoload/classLoader.php";
