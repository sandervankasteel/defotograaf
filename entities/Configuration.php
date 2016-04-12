<?php
/**
 * Remco Schipper
 * Date: 05/12/14
 * Time: 12:35
 */

namespace entities;


class Configuration extends Entity {
    protected $configuration_key = null;
    protected $configuration_value = null;

    public static $table = 'configurations';
    public static $id_field = 'configuration_key';

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->configuration_value;
    }

    /**
     * @param $value
     */
    public function setValue($value) {
        $this->configuration_value = $value;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->configuration_key;
    }
} 