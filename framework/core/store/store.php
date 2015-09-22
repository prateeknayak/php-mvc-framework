<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 20/09/15
 * Time: 10:21 AM
 */

namespace Lp\Framework\Core\Store;


interface Store
{
    public static function getInstance();

    public function getFromStore($key);

    public function saveInStore($key, $value);
}


