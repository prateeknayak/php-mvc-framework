<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 20/08/15
 * Time: 8:30 PM
 */

namespace Lp\Framework\Core;


class coreUtils
{
    private function __construct() {}
    private function __clone() {}

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

    public static function dump_it_out($var)
    {
        xdebug_var_dump($var);
    }
}