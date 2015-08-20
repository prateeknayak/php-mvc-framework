<?php

namespace Lp\Framework\Core;

/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 19/08/15
 * Time: 7:28 PM
 */
use Lp\Framework\Core\Router as router;

class lp
{
    public function __construct()
    {
        echo "calling lp";
    }

    public function letsRoll()
    {
        $caArray = router::get("/");
    }
}