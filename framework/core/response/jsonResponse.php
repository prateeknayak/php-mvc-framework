<?php
/**
 * Created by PhpStorm.
 * User: Prateek Nayak
 * Date: 6/09/15
 * Time: 1:44 PM
 */

namespace Lp\Framework\Core\Response;

/**
 * Class JsonResponse returns the response as json
 *
 * @author Prateek Nayak <foookat_prateek@gmail.com>
 * @package Lp\Framework\Core\Response
 */
class JsonResponse extends Response
{
    /**
     * Let the parent create the object to be used.
     * @param Array $payload refer parent
     * @param int $status refer parent
     * @param mixed $msg refer parent
     */
    public function __construct($payload, $status, $msg)
    {
        parent::__construct($payload, $status, $msg);
    }

    /**
     * Get the response array from parent.
     * add some headers, json encode and return.
     */
    public function sendResponse()
    {
        $this->addHeaders();
        echo json_encode($this->buildResponseArray(), JSON_FORCE_OBJECT);
    }

    /**
     * Adds json response headers.
     */
    public function addHeaders()
    {
        // TODO added json response headers.
    }

}