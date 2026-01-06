<?php

namespace Crmeb\Support;


class Str
{
    /**
     * @param string $haystack
     * @param $needles
     * @return bool
     */
    public static function endsWith(string $haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            if (substr($haystack, -strlen($needle)) === (string)$needle) {
                return true;
            }
        }
        return false;
    }

    /**
     *
     * @param string $haystack
     * @param $needles
     * @return bool
     */
    public static function startsWith(string $haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            if ('' !== $needle && substr($haystack, 0, strlen($needle)) === (string)$needle) {
                return true;
            }
        }

        return false;
    }
}