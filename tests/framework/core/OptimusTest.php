<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 5/09/15
 * Time: 10:23 AM
 */


class OptimusTest extends PHPUnit_Framework_TestCase
{
    private $optimus;
    public function setup()
    {
    }

    public function testCheckFileExists()
    {
//        (new ClassLoader(BASE_PATH))->register();

        $this->optimus = new Optimus();

        echo "running";
//        var_dump($this->optimus->checkFileExits);
    }

}
