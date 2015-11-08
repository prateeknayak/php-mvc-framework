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
            if (preg_match_all('/{+(.*?)}/', $part, $matches)) {
               $params[] = array("position"=>$key, "param"=>$matches[1][0]);
            } else {
                $segmentsToMatch[] = $key;
            }
        }
        self::$routes[] = compact("verb", "keyLen", "explodedRoute", "route", "path", "params", "segmentsToMatch");
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
            if ($requestMethod === $route["verb"] && count($explodedURI) === $route["keyLen"]) {
                $numberOfSegmentsToMatch = count($route["segmentsToMatch"]);
                foreach($route['segmentsToMatch'] as $segment) {
                    if ($route['explodedRoute'][$segment] !== $explodedURI[$segment]) {
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
    private static function explodeRoute($route)
    {
        return explode("/", ltrim(rtrim($route, "/"), "/"));
    }

}