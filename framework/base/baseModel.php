<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 20/08/15
 * Time: 8:29 PM
 */

namespace Wbd\Framework\Base;


abstract class BaseModel
{
    public function init($params = array())
    {
        // implemented by child class
    }
}