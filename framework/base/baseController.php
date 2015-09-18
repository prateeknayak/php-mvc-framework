<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 20/08/15
 * Time: 8:29 PM
 */

namespace Lp\Framework\Base;

use Lp\Framework\Core\Response;
use Lp\Framework\Exceptions\DuplicateFileNameException;
use Lp\Framework\Core\Response\JSONResponse;

/**
 * Abstract base controller which has basic methods
 * for all the application controllers.
 *
 * Class BaseController
 * @package Lp\Framework\Base
 */
abstract class BaseController
{
    use BaseFunctions;

    /**
     * Generic response function for all controllers.
     * usage: $this->response(array());
     * @param array $payload
     */
    protected function response(Array $payload)
    {
//        (new JSONResponse())->sendResponse(Response::JSON_RESPONSE, $payload);
    }


    protected function loadModel($model, $param = array())
    {
        $model = $this->cast("BaseModel", $this->load($model, Store::STORE_TYPE_MODEL));
        $model->init($param);
        return $model;
    }


    protected function loadLibrary($library, $param = array())
    {
        $library = $this->load($library, Store::STORE_TYPE_LIBRARY);
        if(!empty($param) && method_exists($library, "init")) {
            $library->init($param);
        }
        return $library;
    }

    /**
     * @param null $name
     * @param $storeType
     * @return bool
     * @throws \Exception
     */
    private function load($name = null, $storeType)
    {
        $object = null;
        try {
            $object = Store::getFromStore($storeType, $name);
            if (!$object) {
                $fileInfo = $this->checkFileExists($name, $storeType);
                $object = new $fileInfo['fullFileName'];
            }
        } catch(DuplicateFileNameException $dfne) {
            throw new \Exception("File not found $dfne");
        }
        if (is_null($object) || !($object instanceof \stdClass)) {
            throw new \Exception("Class {$name} not found");
        }
        return $object;
    }
}