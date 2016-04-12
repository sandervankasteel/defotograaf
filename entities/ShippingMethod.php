<?php
/**
 * Created by PhpStorm.
 * User: Joey
 * Date: 1-12-2014
 * Time: 15:15
 */

namespace entities;

class ShippingMethod extends Entity
{
    protected $shipping_id = null;
    protected $name = null;
    protected $price = null;
    protected $is_active = null;

    public static $table = 'shipping_methods';
    public static $id_field = 'shipping_id';

    private $_orders = false;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param int $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @param bool $value
     */
    public function setActive($value) {
        $this->is_active = $value;
    }

    /**
     * @return null|bool
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
            $this->_orders = Order::getBy('shipping_method', $this->getId());
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