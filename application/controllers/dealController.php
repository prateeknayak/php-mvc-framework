<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 16/09/15
 * Time: 10:31 PM
 */

namespace Wbd\Application\Controllers;

use Wbd\Framework\Base\BaseController;

class dealController extends BaseController
{
    public function getDeal()
    {
        $this->loadModel("dealModel");
    }

}