<?php
/**
 * Created by PhpStorm.
 * User: Joey
 * Date: 1-12-2014
 * Time: 16:16
 */

namespace entities;

class PaymentMethod extends Entity
{
    protected $payment_method_id = null;
    protected $name = null;
    protected $description = null;
    protected $is_active = null;

    public static $table = 'payment_methods';
    public static $id_field = 'payment_method_id';

    private $_orders = false;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $value
     */
    public function setName($value)
    {
        $this->name = $value;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $value
     */
    public function setDescription($value) {
        $this->description = $value;
    }

    /**
     * @param bool $value
     */
    public function setActive($value) {
        $this->is_active = $value;
    }

    /**
     * @return bool|null
     */
    public function isActive() {
        return $this->is_active;
    }

    /**
     * @return null|Order[]
     * @throws \exceptions\database\Prepare
     */
    public function getOrders() {
        if ($this->_orders === false) {
            $this->_orders = Order::getBy('payment_method_id', $this->getId());
        }

        return $this->_orders;
    }

    /**
     * @return null|static[]
     * @throws \exceptions\database\Prepare
     */
    public static function getInactive() {
        /** @var \entities\Entity $class */
        $class = get_called_class();
        $query = sprintf('SELECT * FROM %s WHERE is_active = 0 ORDER BY name', self::$table);

        return $class::getByQuery($query);
    }

    /**
     * @return null|static[]
     * @throws \exceptions\database\Prepare
     */
    public static function getActive() {
        /** @var \entities\Entity $class */
        $class = get_called_class();
        $query = sprintf('SELECT * FROM %s WHERE is_active = 1 ORDER BY name', self::$table);

        return $class::getByQuery($query);
    }
}