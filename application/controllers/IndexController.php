<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 22/08/15
 * Time: 10:18 PM
 */

namespace Lp\Application\Controllers;


class IndexController
{
    public function getIndex()
    {
        echo json_encode(array("msg"=>"here here"));
    }
}