<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 20/09/15
 * Time: 10:17 AM
 */

namespace Lp\Framework\Core\Store;


class LibraryStore extends StoreParent
{
    const DIR_TO_SEARCH = "library";
    /**
     * Static array which stores loaded libraries
     * @var array
     */
    private static $libraryStore = array();

    /**
     * Make sure only one instance of this class ever exists.
     * @var
     */
    private static $_instance;

    /**
     * make this class static
     */
    private function __construct(){}

    /**
     * Standard get instance method.
     * if instance is null create own object else return instance
     *
     * @return libraryStore
     */
    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Gets the library object if exist in the store.
     *
     * @param $key mixed
     * @return bool
     */
    public function getFromStore($key)
    {
        if (in_array($key, self::$libraryStore)) {
            return self::$libraryStore[$key];
        } else {
            return false;
//            return $this->getFromFileStoreAndSave($key, self::DIR_TO_SEARCH_LIBRARY);
        }
    }

    /**
     * Save the model object against key.
     *
     * @param $key
     * @param $value
     * @return bool
     */
    public function saveInStore($key, $value)
    {
        $this->checkKeyValue($key, $value);
        self::$libraryStore[$key] = $value;
        return true;
    }

    private function __clone(){}
}