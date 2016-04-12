<?php
session_start();
/**
 * Remco Schipper / Tim Oosterbroek
 * Date: 13/11/14
 * Time: 12:01
 */

/**
 * Automatically load a PHP file based on the namespace + class name
 * @param String $class
 */
function autoLoader($class) {
    $parts = explode('\\', $class);
    $parent = dirname(dirname(__FILE__));

    $path = implode(DIRECTORY_SEPARATOR, $parts);

    require_once($parent . DIRECTORY_SEPARATOR . $path . '.php');
}

spl_autoload_register('autoLoader');

$router = \services\Router::getInstance();
$router->work();