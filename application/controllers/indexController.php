<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 22/08/15
 * Time: 10:18 PM
 */

namespace Wbd\Application\Controllers;


use Wbd\Framework\Base\baseController;

class IndexController extends BaseController
{
    public function getIndex()
    {
        echo json_encode(array("msg"=>"here here"));
    }

    public function getBlah()
    {
       $this->response(array("msg"=>"blah blah blah"), 2020, "success");
    }

    public function getDeal($id)
    {
        $this->loadModel("IndexModel");
    }

}