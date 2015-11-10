<?php
namespace Lp\Framework\Core\Request;

/**
 * This is the brain of Optimus, where the path to complete a request is deciphered
 * from Request URI.
 *
 * @author Prateek Nayak <prateek.1708@gmailcom>
 * @package Framework\Core\Request
 */


class Router
{
    const ROUTES_KEY_VERB = "verb";
    const ROUTES_KEY_KEY_LEN = "keyLen";
    const ROUTES_KEY_EXPLODED_ROUTE = "explodedRoute";
    const ROUTES_KEY_ROUTE = "route";
    const ROUTES_KEY_PATH = "path";
    const ROUTES_KEY_PARAMS = "params";
    const ROUTES_KEY_SEGMENTS_TO_MATCH = "segmentsToMatch";
    const ROUTE_REGEX_TO_MATCH = '/{+(.*?)}/';
    const PARAMS_KEY_POSITION = 'position';
    const PARAMS_KEY_PARAM = 'param';
    /**
     * keyLen: int
     * explodedRoute: array()
     * route: String
     * path: array(c, a)
     * @var array
     */
    private static $routes =array();
    private function __construct(){}
    private function __clone(){}

    /**
     * So we called it map reduce. It is not the implementation of map reduce algorithn.
     * But that is what we are calling it EOS.
     *
     * Map is where we map possible routes to Controllers and Functions.
     *
     * @param $verb Request Method
     * @param $route Request URI
     * @param $controller String name of controller which responds to the request
     * @param $action String name of the functino which performs the action
     */
    public static function map($verb, $route, $controller, $action)
    {
        $action = strtolower($verb).ucfirst($action);
        $path = compact("controller", "action");
        $explodedRoute = self::explodeRoute($route);
        $keyLen = count($explodedRoute);
        $params = array();
        $segmentsToMatch =array();
        foreach ($explodedRoute as $key=> $part) {
            if (preg_match_all(self::ROUTE_REGEX_TO_MATCH, $part, $matches)) {
               $params[] = array(self::PARAMS_KEY_POSITION=>$key, self::PARAMS_KEY_PARAM=>$matches[1][0]);
            } else {
                $segmentsToMatch[] = $key;
            }
        }
        self::$routes[] = compact(  self::ROUTES_KEY_VERB,
                                    self::ROUTES_KEY_KEY_LEN,
                                    self::ROUTES_KEY_EXPLODED_ROUTE,
                                    self::ROUTES_KEY_ROUTE,
                                    self::ROUTES_KEY_PATH,
                                    self::ROUTES_KEY_PARAMS,
                                    self::ROUTES_KEY_SEGMENTS_TO_MATCH
                            );
    }

    /**
     * This is the counter part of map function above. It reduces
     * the requestMethod and requestURI in actionable Controller and Function.
     *
     * @param $requestMethod String http request verb
     * @param $requestURI String URI requested
     * @param null $optional
     * @return mixed
     * @throws \RouteNotFoundException
     */
    public static function reduce($requestMethod, $requestURI, $optional = null)
    {
        $explodedURI = self::explodeRoute($requestURI);
        $matchFound = false;
        $matchedIndex= null;
        foreach(self::$routes as $key => $route) {
            if ($requestMethod === $route[self::ROUTES_KEY_VERB] && count($explodedURI) === $route[self::ROUTES_KEY_KEY_LEN]) {
                $numberOfSegmentsToMatch = count($route[self::ROUTES_KEY_SEGMENTS_TO_MATCH]);
                foreach($route[self::ROUTES_KEY_SEGMENTS_TO_MATCH] as $segment) {
                    if ($route[self::ROUTES_KEY_EXPLODED_ROUTE][$segment] !== $explodedURI[$segment]) {
                       break;
                   } else {
                       --$numberOfSegmentsToMatch;
                   }
                }
                if($numberOfSegmentsToMatch==0) {
                    $matchFound = true;
                    $matchedIndex = $key;
                    break;
                }
            }
        }
        if ($matchFound && !is_null($matchedIndex)) {
            return self::$routes[$matchedIndex];
        } else {
            throw new \RouteNotFoundException('I guess we need sherlock in here. Where you trying to go mate?');
        }
    }

    /**
     * Explode given route
     * @param $route
     * @return array
     */
    private static function explodeRoute($route)
    {
        return explode("/", ltrim(rtrim($route, "/"), "/"));
    }

}