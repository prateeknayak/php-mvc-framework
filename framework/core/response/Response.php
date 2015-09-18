<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 19/08/15
 * Time: 7:34 PM
 */

namespace Lp\Framework\Core\Response;
use Lp\Framework\Base\Dispatcher;

abstract class Response
{
    protected $payload = array();
    protected $msg = "";
    protected $status = Dispatcher::SUCCESS;
    protected $responseType = Dispatcher::JSON_RESPONSE;

    public function __construct($payload, $status, $msg)
    {
        $this->payload = $payload;
        $this->status = $status;
        $this->msg = $msg;
    }

    protected function buildResponseArray()
    {
        return array('status'=>$this->status, 'msg'=>$this->msg, 'payload'=>$this->payload);
    }

    abstract function sendResponse();
    abstract function addHeaders();
}