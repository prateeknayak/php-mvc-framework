<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 20/08/15
 * Time: 8:30 PM
 */

namespace Wbd\Framework\Core;


class coreUtils
{

    private function __construct() {}

    /**
     * Very basic purify function.
     * looks for just the script or style tags.
     * @param $dirty
     * @return mixed
     */
    public static function purify($dirty)
    {
        $search =   array(
            '@<script[^>]*?>.*?</script>@si',
            '@<[\/\!]*?[^<>]*?>@si',
            '@<style[^>]*?>.*?</style>@siU',
            '@<![\s\S]*?--[ \t\n\r]*>@'
        );

        return preg_replace($search, '', $dirty);
    }

    /**
     * // TODO implement a generic dump function.
     * @param $var
     */
    public static function dump_it_out($var)
    {
        xdebug_var_dump($var);
    }

    private function __clone() {}
}