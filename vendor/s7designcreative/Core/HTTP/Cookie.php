<?php

namespace S7D\Core\HTTP;


use S7D\Core\Helpers\ArrayDot;

/**
 * Class Cookie
 *
 * Responsible for operations over cookies in framework, from this class its enabled to get and set cookies
 *
 * @package S7D\Core\HTTP
 */
class Cookie
{
    /**
     * Get cookie
     *
     * With cookie name specified get value, if there is no such key return whatever is default value
     *
     * @param string $name    Name of cookie that you want to get
     * @param mixed  $default Fallback value if cookie is not found
     *
     * @return mixed Returns value if is found, if not returns whatever is passed as default
     */
    public static function getCookieByName($name, $default = false)
    {
        return ArrayDot::get($_COOKIE, $name, $default);
    }

}