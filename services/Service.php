<?php
/**
 * Remco Schipper
 * Date: 14/11/14
 * Time: 11:01
 */

namespace services;


class Service {
    public static $instances = array();

    /**
     * @return static
     */
    public static function getInstance() {
        $class = get_called_class();

        if (isset(self::$instances[$class]) === false) {
            self::$instances[$class] = new $class();
        }

        return self::$instances[$class];
    }
} 