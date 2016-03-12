<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 17/09/15
 * Time: 11:41 PM
 */

namespace Wbd\Framework\Core\Store;


class StoreKeeper
{
    const STORE_TYPE_CONTROLLER = 1;
    const STORE_TYPE_MODEL  = 2;
    const STORE_TYPE_LIBRARY = 3;
    const STORE_TYPE_FILE = 4;

    private static $fileStore = array();

    public static function getFromStore($storeType, $key)
    {
        if ($store = self::storeSwitcher($storeType)) {
            // can be combined in to one liner but
            // lets maintain read-ability for now.
            if ($object = $store->getFromStore($key)) {
                return $object;
            } elseif ($object = $store->getFileAndPutWithStoreKeeper($key, $store::DIR_TO_SEARCH)) {
                return $object;
            }
            throw new \Exception("Cannot find the file/class ({$key}) in the application tree.");
        }
        throw new \Exception("Store not found for type {$storeType}");


    }

    private static function storeSwitcher($storeType)
    {
        switch($storeType) {
            case self::STORE_TYPE_CONTROLLER:
                return ControllerStore::getInstance();
                break;
            case self::STORE_TYPE_MODEL:
                return ModelStore::getInstance();
                break;
            case self::STORE_TYPE_LIBRARY:
                return LibraryStore::getInstance();
                break;
            default:
                return false;
        }
    }

    public static function getFileInfoFromStoreKeeper($key)
    {
        if ($fileInfo = self::$fileStore[$key]) {
            return $fileInfo;
        }
        return false;
    }

    public static function putFileInfoWithStoreKeeper($key, $fileInfo)
    {
        self::checkKeyValue($key, $fileInfo);
        self::$fileStore[$key] = $fileInfo;
    }

    private static function checkKeyValue($key, $value)
    {
        if (is_null($key) || empty($key)) {
            throw new \Exception("Illegal key {$key} for the store.");
        }
        if(is_null($value) || empty($value)) {
            throw new \Exception("Illegal value {$value} against key {$key} for the store.");
        }
    }
}