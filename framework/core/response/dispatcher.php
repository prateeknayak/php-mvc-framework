<?php

namespace Lp\Framework\Core\Response;

/**
 * Class Dispatcher used to dispatch response depending
 * on the type of response.
 * Currently supports just json response.
 *
 * @author Prateek Nayak <foookat_prateek@gmail.com>
 * @package Lp\Framework\Core\Response
 */
/**
 * Class Dispatcher
 * @package Lp\Framework\Core\Response
 */
class Dispatcher
{
    /**
     * Use for response type JSON
     */
    const JSON_RESPONSE = 1;

    /**
     * Use for response type HTMl
     */
    const HTML_REPONSE = 2;

    /**
     * Use for response type XML
     */
    const XML_RESPONSE = 3;

    /**
     * Our custom success message.
     * Do not reply on http 200
     */
    const SUCCESS = 2020;

    /**
     * Our custom failure message.
     */
    const FAILURE = 9191;

    /**
     * Dispatcher singleton object.
     * @var self only one object to be used.
     */
    private static $_dispatcher;

    /**
     * Singletonies Dispatcher.
     * @return Dispatcher
     */
    public static function getInstance()
    {
        if (!(self::$_dispatcher instanceof self)) {
            self::$_dispatcher = new self();
        }
        return self::$_dispatcher;
    }

    /**
     * Called from controllers to send JSON Response.
     * This method supports JSON out of the box.
     * XML and HTML to be added in later versions.
     * <p>
     * Depending on the type this method creates type object
     * and then calls the send response.
     *
     * @param array $payload
     * @param $status int
     * @param $msg mixed
     * @param int $type
     * @throws \Exception
     */
    public function sendResponse(array $payload, $status, $msg, $type = self::JSON_RESPONSE)
    {
        // Since this is not java and type hinting is very loose in php
        // we have to roll out our own checks.
        // Though payload will always be forced as an array, still we can
        // check for it to be sure as we have to check others.
        if (!$this->validateConstructorCall($payload, $status, $msg)) {
            throw new \InvalidArgumentException("Invalid arguments supplied to send response method");
        }

        // Dispatcher switch
        switch($type) {
            case self::JSON_RESPONSE:
                (new JsonResponse($payload, $status, $msg))->sendResponse();
                break;
            case self::HTML_REPONSE:
                throw new \Exception("HTML response not allowed in this version");
                break;
            case self::XML_RESPONSE:
                throw new \Exception("XML response not allowed in this version");
                break;
            default:
                throw new \Exception("Su chale mate?? Illegal Response type");
        }
    }

    /**
     * Validates the given parameters for constructor.
     * @param $payload Array and non empty
     * @param $status int
     * @param $msg mixed
     * @return bool weather all params are valid or not
     */
    private function validateConstructorCall($payload, $status, $msg)
    {
        $valid = false;
        if (!empty($payload) && is_array($payload) && is_int($status) && is_string($msg)){
            $valid = true;
        }
        return $valid;
    }
}