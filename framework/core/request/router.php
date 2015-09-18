<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 19/08/15
 * Time: 7:35 PM
 */

namespace Lp\Framework\Core\Request;


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

    public static function reduce($requestMethod, $requestURI, $optional = null)
    {
        $explodedURI = self::explodeRoute($requestURI);
        $matchFound = false;
        $matchedIndex= null;
        foreach(self::$routes as $key => $route) {
            if ($requestMethod === $route["verb"] && count($explodedURI) === $route["keyLen"]) {
                $numnerOfsegmentsToMatch = count($route["segmentsToMatch"]);
                foreach($route['segmentsToMatch'] as $segment) {
                    if ($route['explodedRoute'][$segment] !== $explodedURI[$segment]) {
                       break;
                   } else {
                       --$numnerOfsegmentsToMatch;
                   }
                }
                if($numnerOfsegmentsToMatch==0) {
                    $matchFound = true;
                    $matchedIndex = $key;
                    break;
                }
            }
        }
        if ($matchFound && !is_null($matchedIndex)) {
            return self::$routes[$matchedIndex];
        } else {
            throw new \RouteNotFoundException('I guess we need sherlocks in here. Where you trying to go mate?');
        }
    }
    private static function explodeRoute($route)
    {
        return explode("/", ltrim(rtrim($route, "/"), "/"));
    }

}