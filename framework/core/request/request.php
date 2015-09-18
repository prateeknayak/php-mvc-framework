<?php

namespace Lp\Framework\Core\Request;
use Lp\Framework\Exceptions\EmptyInputArrayException;
use Lp\Framework\Core\CoreUtils as CF;
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 19/08/15
 * Time: 7:33 PM
 */

class Request
{

    private static $inputArray = array();
    private function __construct(){}
    private function __clone(){}

    public static function get($key = null)
    {
        if (count(self::$inputArray)< 1) {
            throw new EmptyInputArrayException("The global arrays are empty please verify");
        } elseif (is_null($key) || $key === "") {
            return self::$inputArray;
        } else if( (count(self::$inputArray) > 0) && isset(self::$inputArray[$key]) ) {
            return self::$inputArray[$key];
        }
    }
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
    // public static function parseJSONInput()
    // {
    //    self::$dirtyInput = json_decode(file_get_contents('php://input'),TRUE);
    // } 

        public static function whereYouWantToGo()
        {
            return cf::purify($_SERVER['REQUEST_URI']);
        }

        public static function howYouWantToGo()
        {
            return $_SERVER['REQUEST_METHOD'];
        }
    }