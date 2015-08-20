<?php
namespace Healthand\Framework\Controller;

use \PDO as PDO;
use \PDOException as PDOException;

class DbSingleton
{
    private static $cmsDB;
    private static $usersDB;
    private static $sessionDB;
     
    public static function igniteCMS($host, $username, $password, $dbName, $emulate = false)
    {
        if (!self::$cmsDB) {
            
            self::$cmsDB = self::init($host, $username, $password, $dbName, $emulate = false);
             
        }
        return self::$cmsDB;
    }

    public static function igniteUsers($host, $username, $password, $dbName, $emulate = false)
    {
        if (!self::$usersDB) {
           self::$usersDB = self::init($host, $username, $password, $dbName, $emulate = false);
        }
        return self::$usersDB;
    }

    public static function igniteSessions($host, $username, $password, $dbName, $emulate = false)
    {
        if (!self::$sessionDB) {
            self::$sessionDB = self::init($host, $username, $password, $dbName, $emulate = false);
        }
        return self::$sessionDB;
    }

    private static function init($host, $username, $password, $dbName, $emulate = false) 
    {
    	$instance = "";
		try {
		  	
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
			throw new Exception('Connection error: ' . $e->getMessage());			
		}
        return $instance;
    }
    public static function destroy()
    {
    	self::$cmsDB = null;
    	self::$usersDB = null;
    	self::$sessionDB = null;
    }
}
