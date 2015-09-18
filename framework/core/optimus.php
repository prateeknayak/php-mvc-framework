<?php

namespace Lp\Framework\Core;

/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 19/08/15
 * Time: 7:28 PM
 */
use Lp\Framework\Base\BaseFunctions;
use Lp\Framework\Core\Request\Router;
use Lp\Framework\Core\Request\Request;
use Lp\Framework\Exceptions\DuplicateFileNameException;
use Lp\Framework\Exceptions\RouteNotFoundException;

class Optimus
{
    use BaseFunctions;


    public function __construct(){}


    /**
     * @throws \LP\Framework\Exceptions\RouteNotFoundException
     * @throws \Lp\Framework\Exceptions\DuplicateFileNameException
     */
    public function letsRoll()
    {
        try {
            $goto = Router::reduce(Request::howYouWantToGo(),  Request::whereYouWantToGo());
            if ($fileInfo = $this->checkFileExists($goto['path']['controller'])) {
                call_user_func_array(array( new $fileInfo['fullFileName'], $goto['path']['action']),
                    array(array_values($goto['params'])));
            }
        } catch(RouteNotFoundException $rnfe) {

        } catch(DuplicateFileNameException $dfne) {

        } catch(\Exception $e) {

        }
    }

    private function __clone(){}

    /**
     *  Cleaning when destructing
     */
    public function __destruct()
    {
        self::$fileStore = null;
    }
}