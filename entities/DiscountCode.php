<?php
/**
 * Created by PhpStorm.
 * User: Joey
 * Date: 2-12-2014
 * Time: 10:59
 */

namespace entities;


class DiscountCode extends Entity
{
    protected $discount_id = null;
    protected $code = null;
    protected $description = null;
    protected $percentage = null;
    protected $fixed_amount = null;
    protected $valid_until = null;
    protected $amount = null;

    public static $table = 'discount_codes';
    public static $id_field = 'discount_id';

    private $_orders = false;

    public function getId() {
        return $this->getDiscountId();
    }

    /**
     * @return int
     */
    public function getDiscountId()
    {
        return $this->discount_id;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getFixedAmount()
    {
        return $this->fixed_amount;
    }

    /**
     * @return int
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    public function setFixedAmount($value) {
        $this->fixed_amount = $value;
    }

    public function setPercentage($value) {
        $this->percentage = $value;
    }

    public function setCode($value) {
        $this->code = $value;
    }

    public function setValidUntil($value) {
        $this->valid_until = $value;
    }

    public function setDescription($value) {
        $this->description = $value;
    }

    public function setAmount($value) {
        $this->amount = $value;
    }

    public function getAmount() {
        return $this->amount;
    }

    /**
     * @return \DateTime
     */
    public function getValidUntil() {
        return new \DateTime($this->valid_until);
    }

    /**
     * @return null|Order[]
     * @throws \exceptions\database\Prepare
     */
    public function getOrders() {
        if ($this->_orders === false) {
            $this->_orders = Order::getBy('discount_code_id', $this->getId());
        }

        return $this->_orders;
    }

    /**
     * @return null|static[]
     * @throws \exceptions\database\Prepare
     */
    public static function getActive() {
        /** @var \entities\Entity $class */
        $class = get_called_class();
        $query = sprintf('SELECT * FROM %s WHERE valid_until > NOW() ORDER BY code', self::$table);

        return $class::getByQuery($query);
    }

    /**
     * @return null|static[]
     * @throws \exceptions\database\Prepare
     */
    public static function getInactive() {
        /** @var \entities\Entity $class */
        $class = get_called_class();
        $query = sprintf('SELECT * FROM %s WHERE valid_until < NOW() ORDER BY code', self::$table);

        return $class::getByQuery($query);
    }
}