<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 22/08/15
 * Time: 10:18 PM
 */

namespace Lp\Application\Controllers;


use Lp\Framework\Base\baseController;
use Lp\Framework\Core\Request\Request;
use Lp\Framework\Core\Request\Router;

class IndexController extends BaseController
{
    public function getIndex()
    {
        echo json_encode(array("msg"=>"here here"));
    }

    public function getBlah()
    {
        echo json_encode(array("msg"=>"blah blah blah"));
    }

    public function getDeal($id)
    {
        $this->loadModel("IndexModel");
    }

}