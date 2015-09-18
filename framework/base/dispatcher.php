<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 6/09/15
 * Time: 1:31 PM
 */

namespace Lp\Framework\Base;
use LP\Framework\Core\Response;

class Dispatcher
{
    const JSON_RESPONSE = 1;
    const HTML_REPONSE = 2;
    const XML_RESPONSE = 3;
    const SUCCESS = 2020;
    const FAILURE = 9191;
    public static function sendResponse($type =self::JSON_RESPONSE, array $payload, $status, $msg)
    {

        $response = new Response($type, $payload, $status, $msg);
        switch($type) {
            case self::JSON_RESPONSE:
                self::sendJSONResponse($response);
                break;
            case self::HTML_REPONSE:
                self::sendHTMLResponse($response);
                break;
            case self::XML_RESPONSE:
                self::sendXMLResponse($response);
                break;
        }
    }

}