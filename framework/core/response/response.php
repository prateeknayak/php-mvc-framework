<?php

namespace Wbd\Framework\Core\Response;

/**
 * Abstract Class Response parent class of all response types
 * does the heavy lifting of building the response array.
 *
 * @author Prateek Nayak <foookat_prateek@gmail.com>
 * @package Lp\Framework\Core\Response
 */
abstract class Response
{
    /**
     * Array to return.
     * passed from controller.
     * @var array
     */
    protected $payload = array();

    /**
     * Message related to success or failure
     * @var string
     */
    protected $msg = "";

    /**
     * Set to success by default.
     * defined in Dispatcher class.
     * @var int
     */
    protected $status = Dispatcher::SUCCESS;

    /**
     * Default response type is json.
     * defined in Dispatcher class.
     * @var int
     */
    protected $responseType = Dispatcher::JSON_RESPONSE;

    /**
     * Create basic response object.
     * Which then can be used by child classes to create
     * response of respective types.
     *
     * @param $payload array to return to the caller.
     * @param $status int success(2020) or failure(9191)
     * @param $msg mixed string literal with generic message.
     */
    public function __construct($payload, $status, $msg)
    {
        $this->payload = $payload;
        $this->status = $status;
        $this->msg = $msg;
    }

    /**
     * Methods which should be overridden in child classes.
     * @return mixed
     */
    abstract function sendResponse();

    abstract function addHeaders();

    /**
     * Builds a response array from the response object props.
     * @return array complete response array.
     */
    protected function buildResponseArray()
    {
        return array('status'=>$this->status, 'msg'=>$this->msg, 'payload'=>$this->payload);
    }
}