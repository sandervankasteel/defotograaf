<?php
/**
 * Created by PhpStorm.
 * User: Joey
 * Date: 1-12-2014
 * Time: 15:52
 */

namespace entities;

class OrderStatus extends Entity
{
    protected $order_status_id = null;
    protected $description = null;
    protected $is_active = null;

    public static $table = 'order_status';
    public static $id_field = 'order_status_id';

    private $_orders = false;

    public function getId() {
        return $this->order_status_id;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value) {
        $this->description = $value;
    }

    public function setActive($value) {
        $this->is_active = $value;
    }

    public function isActive() {
        return $this->is_active;
    }

    /**
     * @return null|Order[]
     * @throws \exceptions\database\Prepare
     */
    public function getOrders() {
        if ($this->_orders === false) {
            $this->_orders = Order::getBy('order_status', $this->getId());
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
        $query = sprintf('SELECT * FROM %s WHERE is_active = 0 ORDER BY description', self::$table);

        return $class::getByQuery($query);
    }

    /**
     * @return null|static[]
     * @throws \exceptions\database\Prepare
     */
    public static function getActive() {
        /** @var \entities\Entity $class */
        $class = get_called_class();
        $query = sprintf('SELECT * FROM %s WHERE is_active = 1 ORDER BY description', self::$table);

        return $class::getByQuery($query);
    }
}