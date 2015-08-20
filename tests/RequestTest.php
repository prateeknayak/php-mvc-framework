<?php
include dirname(__DIR__)."/framework/bootstrap.php";
use Lp\Framework\Core\Request as R;
use PHPunit_Framework_TestCase;
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 19/08/15
 * Time: 9:04 PM
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{

    public function setup()
    {

    }

    public function testGet()
    {
        $_SERVER['REQUEST_METHOD'] = "GET";
        $_GET = array("fname"=>"abcd","fname"=>"abcd","fname"=>"abcd");
        var_dump(R::get());
        var_dump(R::get("fname"));
    }
}
