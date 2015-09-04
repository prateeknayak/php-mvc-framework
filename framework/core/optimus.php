<?php

namespace Lp\Framework\Core;

/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 19/08/15
 * Time: 7:28 PM
 */
use Lp\Framework\Core\Router;

class Optimus
{
    public function __construct()
    {
        echo "calling lp";
    }

    public function letsRoll()
    {
        $whereToGo = Router::reduce();
        var_dump(Router::reduce());
    }
}