<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 4/09/15
 * Time: 6:55 PM
 */

namespace Lp\Framework\Core;

use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;
use \Monolog\Handler\FirePHPHandler;


class LoggerWrapper
{
    const LOG_PATH ="/var/app/fookat/";

    private function __construct(){}
    private function __clone(){}

    private static $logger;

    private function init($channel,$file, $level= Logger::DEBUG)
    {
        $logger = new Logger($channel);
        $logger->pushHandler(new StreamHandler(LOG_PATH.$file, $level));
        $logger->pushHandler(new FirePHPHandler());
        return $logger;
    }

    public static function fkDebug($channel)
    {
        $logger = self::init($channel, "fk_debug.log");
//        $logger->
    }

    public static function fkError()
    {
        self::init();
    }

    public static function fkInfo()
    {
        self::init();
    }

    public static function fkWarn()
    {
        self::init();
    }

    public static function fkCustomLogger()
    {
        self::init();
    }
}