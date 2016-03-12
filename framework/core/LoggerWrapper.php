<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 4/09/15
 * Time: 6:55 PM
 */

namespace Wbd\Framework\Core;

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
    private static $_instance;
    /**
     * Singleton pattern cemented.
     */
    private function __construct(){}

    /**
     * @return LoggerWrapper
     */
    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Singleton pattern cemented.
     */
    private function __clone(){}

    /**
     * Initialise logger for channel and file.
     * @param $channel
     * @param int $level
     * @return Logger
     */
    public function init($channel, $level= Logger::DEBUG)
    {
        switch($level) {
            case Logger::ERROR:
                $logFile = "error.log";
                break;
            case Logger::INFO:
            case Logger::WARNING:
                $logFile = "info.log";
                break;
            case Logger::ALERT:
            case Logger::EMERGENCY:
                $logFile = "emergency.log";
                break;
            default:
                $logFile = "debug.log";
                break;
        }
        $logger = new Logger($channel);
        $logger->pushHandler((new StreamHandler(self::LOG_PATH.self::FILE_PREFIX.$logFile, $level))->setFormatter(new LineFormatter(null, null, true, true)));
        $logger->pushHandler(new FirePHPHandler());
        return $logger;
    }

    /**
     * This method just wraps monolog add debug.
     *
     * @param Logger $logger
     * @param $msg
     * @param array $param
     */
    public function debug(Logger $logger, $msg, $param = array())
    {
        $logger->addDebug($msg, $param);
    }

    /**
     * This method wraps monolog's add error
     * @param Logger $logger
     * @param $msg
     * @param array $param
     */
    public function error(Logger $logger, $msg, $param = array())
    {
        $logger->addError($msg, $param);
    }

    /**
     * This method wraps monolog's add info
     * @param Logger $logger
     * @param $msg
     * @param array $param
     */
    public function info(Logger $logger, $msg, $param = array())
    {
        $logger->addInfo($msg, $param);
    }

    /**
     * This method wraps monolog's add warning
     * @param Logger $logger
     * @param $msg
     * @param array $param
     */
    public function warn(Logger $logger, $msg, $param = array())
    {
        $logger->addWarning($msg, $param);
    }

    /**
     * This method wraps monolog's add alert
     * @param Logger $logger
     * @param $msg
     * @param array $param
     */
    public function alert(Logger $logger, $msg, $param =array())
    {
        $logger->addAlert($msg, $param);
    }

    /**
     * @param Logger $logger
     * @param $msg
     * @param array $param
     */
    public function emergency(Logger $logger, $msg, $param = array())
    {
        $logger->addEmergency($msg, $param);
    }

}