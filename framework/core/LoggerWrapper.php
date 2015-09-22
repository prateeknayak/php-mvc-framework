<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 4/09/15
 * Time: 6:55 PM
 */

namespace Lp\Framework\Core;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;


/**
 * Class LoggerWrapper
 * @package Lp\Framework\Core
 */
class LoggerWrapper
{
    /**
     * Path to generate log files.
     * Should be owned by www-data
     */
    const LOG_PATH ="/var/app/log/";
    /**
     * Prefix to all the log files.
     */
    const FILE_PREFIX = "api_";
    /**
     * instance of self.
     * @var self
     */
    private static $logger;

    /**
     * Singleton pattern cemented.
     */
    private function __construct(){}

    /**
     * @return LoggerWrapper
     */
    public static function getInstance()
    {
        if (!(self::$logger instanceof self)) {
            self::$logger = new self();
        }
        return self::$logger;
    }

    /**
     * Singleton pattern cemented.
     */
    private function __clone(){}

    /**
     * Initialise logger for channel and file.
     * @param $channel
     * @param $file
     * @param int $level
     * @return Logger
     */
    private function init($channel,$file, $level= Logger::DEBUG)
    {
        $logger = new Logger($channel);
        $logger->pushHandler((new StreamHandler(self::LOG_PATH.$file, $level))->setFormatter(new LineFormatter(null, null, true, true)));
        $logger->pushHandler(new FirePHPHandler());
        return $logger;
    }
}