<?php
/**
 * Remco Schipper
 * Date: 14/11/14
 * Time: 10:20
 */

namespace services;

use config\Database AS config;
use exceptions\database\Connection;

class Database extends Service {
    private $mysqli = null;

    /**
     * Creates the mysqli connection to the server specified in the config file
     * @throws Connection
     * @return \mysqli
     */
    private function connect() {
        if ($this->mysqli === null) {
            $this->mysqli = new \mysqli(config::$HOST, config::$USERNAME, config::$PASSWORD, config::$DATABASE);

            if ($this->mysqli->connect_errno) {
                throw new Connection();
            }
        }

        return $this->mysqli;
    }

    /**
     * @return \mysqli
     */
    public static function get() {
        return self::getInstance()->connect();
    }
} 