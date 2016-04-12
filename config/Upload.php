<?php
/**
 * Remco Schipper
 * Date: 13/12/14
 * Time: 17:56
 */

namespace config;


class Upload {
    public static function getUploadDir() {
        return implode(DIRECTORY_SEPARATOR, array(dirname(dirname(__FILE__)), 'assets', 'uploads'));
    }
} 