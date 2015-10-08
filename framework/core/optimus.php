<?php

namespace Lp\Framework\Core;

/**
 * Created by PhpStorm.
 * User: Prateek Nayak
 * Date: 19/08/15
 * Time: 7:28 PM
 */
use Lp\Application\Controllers\IndexController;
use Lp\Framework\Base\BaseFunctions;
use Lp\Framework\Core\Request\Request;
use Lp\Framework\Core\Request\Router;
use Lp\Framework\Core\Store\StoreKeeper;
use Lp\Framework\Exceptions\DuplicateFileNameException;
use Lp\Framework\Exceptions\RouteNotFoundException;
use Monolog\Logger;

class Optimus
{
    use BaseFunctions;

    const DEFAULT_ACTION = "getIndex";

    private static $LOGGER;

    public function __construct()
    {
        self::$LOGGER = LoggerWrapper::getInstance()->init(__CLASS__, Logger::DEBUG);
    }
    /**
     * Heart of the application.
     * Optimus takes you through application as per url.
     *
     * @throws \LP\Framework\Exceptions\RouteNotFoundException
     * @throws \Lp\Framework\Exceptions\DuplicateFileNameException
     */
    public function letsRoll()
    {
        try {
            $goto = Router::reduce(Request::howYouWantToGo(),  Request::whereYouWantToGo());
            $classArray = array();
            if ($controller = StoreKeeper::getFromStore(StoreKeeper::STORE_TYPE_CONTROLLER, $goto['path']['controller'])) {
                $classArray = array($controller, $goto['path']['action']);
            } elseif (empty($classArray)) {
                $classArray = array(new IndexController(), self::DEFAULT_ACTION);
            }

            call_user_func_array($classArray, $goto['params']);
        } catch(RouteNotFoundException $rnfe) {

            var_dump($rnfe);
        } catch(DuplicateFileNameException $dfne) {
            var_dump($dfne);
        } catch(\Exception $e) {
            var_dump($e);
        }
    }
}