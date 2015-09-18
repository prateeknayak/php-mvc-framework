<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 6/09/15
 * Time: 1:44 PM
 */

namespace Lp\Framework\Core\Response;


class JSONResponse extends Response
{
    public function __construct($payload, $status, $msg)
    {
        parent::__construct($payload, $status, $msg);
    }
    public function sendResponse()
    {
        echo json_encode($this->buildResponseArray(), JSON_FORCE_OBJECT);
    }

    public function addHeaders()
    {
    
    }

}