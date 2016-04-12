<?php
/**
 * Tim Oosterbroek
 * Date: 18/11/14
 * Time: 13:27
 *
 * Joey Smit
 * Date: 01/12/14
 * Time: 21:42
 */
namespace entities;


class Order extends Entity{
    protected $order_id = null;
    protected $user_id = null;
    protected $shipping_address = null;
    protected $shipping_method = null;
    protected $payment_method_id = null;
    protected $discount_code_id = null;
    protected $order_status = null;
    protected $starting_costs = null;
    protected $order_placed = null;

    public static $table = 'orders';

    private $_shipping_address = null;
    private $_user = null;
    private $_products = null;
    private $_starting_costs = null;
    private $_shipping_method = null;
    private $_order_status = null;
    private $_payment_method = null;
    private $_discount_code = null;


    public function setStatus($status) {
        $this->order_status = $status->getId();
    }



    /**
     * @return \DateTime
     */
    public function getOrderPlaced()
    {
        return new \DateTime($this->order_placed);
    }



    /**
     * @return null|\entities\Address
     */
    public function getShippingAddress() {
        if ($this->_shipping_address === null) {
            $this->_shipping_address = Address::getById($this->shipping_address);
        }
        return $this->_shipping_address;
    }

    /**
     * @return null|\entities\User
     */
    public function getUser() {
        if ($this->_user === null) {
            $this->_user = User::getById($this->user_id);
        }
        return $this->_user;
    }

    public function getLines() {
        if ($this->_products === null) {
            $this->_products = OrderProduct::getBy('order_id', $this->order_id, false);
        }

        return $this->_products;
    }

    /**
     * @return null|\entities\StartingCost
     */
    public function getStartingCost()
    {
        if ($this->_starting_costs === null) {
            $this->_starting_costs = StartingCost::getBy('starting_costs_id', $this->starting_costs, TRUE);
        }
        return $this->_starting_costs;
    }

    /**
     * @return null|\entities\ShippingMethod
     */
    public function getShippingMethod()
    {
        if ($this->_shipping_method === null) {
            $this->_shipping_method = ShippingMethod::getBy('shipping_id', $this->shipping_method, TRUE);
        }
        return $this->_shipping_method;
    }

    /**
     * @return null|\entities\OrderStatus
     */
    public function getOrderStatus()
    {
        if ($this->_order_status === null) {
            $this->_order_status = OrderStatus::getBy('order_status_id', $this->order_status, TRUE);
        }
        return $this->_order_status;
    }

    /**
     * @return null|\entities\PaymentMethod
     */
    public function getPaymentMethod()
    {
        if ($this->_payment_method === null) {
            $this->_payment_method = PaymentMethod::getBy('payment_method_id', $this->payment_method_id, TRUE);
        }
        return $this->_payment_method;
    }

    public function setDiscountCode($id) {
        $this->discount_code_id = $id;
    }

    /**
     * @return null|\entities\DiscountCode
     */
    public function getDiscountCode()
    {
        if ($this->_discount_code === null) {
            $this->_discount_code = DiscountCode::getBy('discount_id', $this->discount_code_id, TRUE);
        }
        return $this->_discount_code;
    }
} 