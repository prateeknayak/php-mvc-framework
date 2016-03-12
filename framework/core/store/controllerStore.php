<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 21/09/15
 * Time: 6:43 PM
 */

namespace Wbd\Framework\Core\Store;


class ControllerStore extends StoreParent
{
    const DIR_TO_SEARCH = "controllers";
    /**
     * Static array which stores loaded files
     * @var array
     */
    private static $controllerStore = array();
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
     * @return ControllerStore
     */
    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Gets the class definition array for given class Or false.
     *
     * @param $key mixed
     * @return bool
     */
    public function getFromStore($key)
    {
        if (in_array($key, self::$controllerStore)) {
            return self::$controllerStore[$key];
        } else {
            return false;
//            return $this->getFromFileStoreAndSave($key, self::DIR_TO_SEARCH_CONTROLLER);
        }
    }

    /**
     * Save the class definition array against key.
     *
     * @param $key
     * @param $value
     * @return bool
     */
    public function saveInStore($key, $value)
    {
        $this->checkKeyValue($key, $value);
        self::$controllerStore[$key] = $value;
        return $value;

    }

    private function __clone(){}
}