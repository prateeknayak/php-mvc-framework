<?php

namespace Wbd\Framework\Core\Request;
use Wbd\Framework\Exceptions\EmptyInputArrayException;
use Wbd\Framework\Core\CoreUtils as CF;
/**
 * Class to parse all the requests received by our framework
 *
 * @author Prateek Nayak <prateek.1708@gmail.com>
 * @package Framework\Core\Request
 */

class Request
{
    /**
     * Collection of input variables.
     * @var array
     */
    private static $inputArray = array();
    private function __construct(){}
    private function __clone(){}

    /**
     * Static function which can be called through out the application
     * to access input variables.
     *
     * @param null $key name of the input var passed to app
     * @return array|mixed return entire array if key is null or return specific value
     * @throws EmptyInputArrayException
     */
    public static function get($key = null)
    {
        if (count(self::$inputArray)< 1) {
            throw new EmptyInputArrayException("The global arrays are empty please verify");
        } elseif (is_null($key) || "" === $key) {
            return self::$inputArray;
        } else if((count(self::$inputArray) > 0) && isset(self::$inputArray[$key]) ) {
            return self::$inputArray[$key];
        }
    }

    /**
     * Creates inputArray after sanitising globals
     */
    public static function cleanTheGlobals()
    {
        $filterGlobal = function($filter = INPUT_GET) {
            $global = filter_input_array($filter, FILTER_SANITIZE_STRING);
            if (count($global) > 0) {
                foreach ($global as $key => $value) {
                    self::$inputArray[cf::purify($key)] = cf::purify($value);
                }
            }
        };

        if ('GET' === $_SERVER['REQUEST_METHOD']) {
            $filterGlobal();
        } else if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $filterGlobal(INPUT_POST);
            $filterGlobal(INPUT_GET);
        }
            
        $destroyGlobals = function (){
            $_GET =  array();
            $_POST = array();
            $_REQUEST= array();
        };

        $destroyGlobals();
    }

    /**
     * inputCan also be supplied as pure json.;
     */
    public static function parseJSONInput()
    {
        //TODO: implement Json as associative array extractor.
        //self::$dirtyInput = json_decode(file_get_contents('php://input'),TRUE);
    }

    /**
     * Return the URI requested.
     * Apparently that is where the request wants to go.
     *
     * @return mixed
     */
    public static function whereYouWantToGo()
    {
        return cf::purify($_SERVER['REQUEST_URI']);
    }

    /**
     * Mode of transport for the request
     * @return mixed
     */
    public static function howYouWantToGo()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}