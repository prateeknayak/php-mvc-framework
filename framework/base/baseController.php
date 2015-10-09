<?php
namespace Lp\Framework\Base;

use Lp\Framework\Core\Response;
use Lp\Framework\Core\Response\Dispatcher;
use Lp\Framework\Core\Store\StoreKeeper;
use Lp\Framework\Exceptions\DuplicateFileNameException;

/**
 * Abstract base controller which has basic methods
 * for all the application controllers.
 *
 * Class BaseController
 * @author Prateek Nayak <prateek.1708@gmail.com>
 * @package Lp\Framework\Base
 */
abstract class BaseController
{
    use BaseFunctions;

    /**
     * Generic response function for all controllers.
     * usage: $this->response(array());
     *
     * @param array $payload
     * @param $status
     * @param $msg
     * @param int $type
     * @throws \Exception
     */
    protected function response(array $payload, $status, $msg, $type = Dispatcher::JSON_RESPONSE)
    {
        if (empty($payload)) {
            throw new \Exception("What are we trying to do here ?? Sending empty payload ?");
        }

        if (empty($status)) {
            throw new \Exception("Missing status from response");
        }

        if (empty($msg)) {
            $msg = "We hate you hence no message for you.";
        }
        try {
            Dispatcher::getInstance()->sendResponse($payload, $status, $msg, $type);
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * Method called from controllers to load a specific model file.
     * All models to be placed in application/models dir.
     *
     * @param mixed $model name of the model to load
     * @param array $param any init params to pass.
     * @return BaseModel $model model object.
     * @throws \Exception
     */
    protected function loadModel($model, $param = array())
    {
        /** @var BaseModel $model */
        $model = $this->cast("BaseModel", $this->load($model, StoreKeeper::STORE_TYPE_MODEL));
        $model->init($param);
        return $model;
    }

    /**
     * Method called from controllers to load a library.
     * All libraries should be placed in application/library dir.
     * @param $library
     * @param array $param
     * @return bool
     * @throws \Exception
     */
    protected function loadLibrary($library, $param = array())
    {
        $library = $this->load($library, StoreKeeper::STORE_TYPE_LIBRARY);
        if(!empty($param) && method_exists($library, "init")) {
            $library->init($param);
        }
        return $library;
    }

    /**
     * Common load function.
     *
     * @param null $name name of the class to load.
     * @param int $storeType type of class store.
     * @return null|\stdClass $object
     * @throws \Exception
     */
    private function load($name = null, $storeType)
    {
        $object = null;
        try {
            $object = StoreKeeper::getFromStore($storeType, $name);
        } catch(DuplicateFileNameException $dfne) {
            throw new \Exception("File not found $dfne");
        } catch (\Exception $e) {
            throw $e;
        }
        if (is_null($object) || !($object instanceof BaseModel)) {
            throw new \Exception("Class {$name} not found");
        }
        return $object;
    }
}
