<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 17/09/15
 * Time: 11:41 PM
 */

namespace Lp\Framework\Base;


class Store
{
    const STORE_TYPE_CONTROLLER = 1;
    const STORE_TYPE_MODEL  = 2;
    const STORE_TYPE_LIBRARY = 3;
    const STORE_TYPE_FILE =4;

    private static $modelStore = array();
    private static $controllerStore = array();
    private static $libraryStore = array();
    private static $fileStore = array();

    public static function getFromStore($storeType, $key)
    {
       return self::isInStore(self::storeSwitcher($storeType), $key);
    }

    public static function saveInStore($storeType, $key, $object)
    {
        $store = self::storeSwitcher($storeType);
        if ($store && !(in_array($object, $store, true))) {
            $store[$key]  = $object;
        }
        throw new \Exception("Store not found or object exist");
    }

    private static function isInStore($store, $key)
    {
        $return = false;
        if ($store && array_key_exists($key, $store) && !empty($store[$key]) && ($store[$key] instanceof \stdClass)) {
            $return = $store[$key];
        }
        return $return;

    }
    private function storeSwitcher($storeType)
    {
        switch($storeType) {
            case self::STORE_TYPE_CONTROLLER:
                return self::$controllerStore;
                break;
            case self::STORE_TYPE_MODEL:
                return self::$modelStore;
                break;
            case self::STORE_TYPE_LIBRARY:
                return self::$libraryStore;
                break;
            case self::STORE_TYPE_GENERIC;
                return self::$fileStore;
                break;
            default:
                return false;
        }
    }
}