<?php
/**
 * Remco Schipper
 * Date: 12/12/14
 * Time: 11:17
 */

namespace services;


use config\Text as config;

class Text extends Service {
    private $text = null;

    public function __construct() {
        $this->text = config::$values;
    }

    public function resolve($text, array $params = null) {
        $t = $this->find(explode('.', $text), $this->text);

        if ($t !== null && $params !== null) {
            $t = vsprintf($t, $params);
        }

        return $t;
    }

    public static function get($text, array $params = null) {
        $instance = self::getInstance();

        return $instance->resolve($text, $params);
    }

    private function find(array $keys, array $array){
        $key = array_shift($keys);
        if(!isset($array[$key])) return null;
        return empty($keys) ?
            $array[$key]:
            $this->find($keys,$array[$key]);
    }
} 