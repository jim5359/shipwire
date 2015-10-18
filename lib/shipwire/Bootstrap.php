<?php

/**
 * Class Bootstrap
 */
class Bootstrap
{
    public static function autoload($class)
    {
        require_once 'lib' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    }
}
spl_autoload_register('\Bootstrap::autoload');
