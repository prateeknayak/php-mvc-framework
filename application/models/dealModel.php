<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 22/09/15
 * Time: 11:51 AM
 */

namespace Wbd\Application\Models;


use Wbd\Framework\Base\BaseModel;

class DealModel extends BaseModel
{
    public function __construct()
    {
        echo "constructing model";
    }
    public function index()
    {
        echo "in the model";
    }
}