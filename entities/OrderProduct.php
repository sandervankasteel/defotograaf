<?php
/**
 * Created by PhpStorm.
 * User: Joey
 * Date: 27-11-2014
 * Time: 11:54
 */

namespace entities;


class OrderProduct extends Entity
{
    protected $order_product_id = null;
    protected $order_id = null;
    protected $product_id = null;
    protected $photo_id = null;
    protected $amount = null;
    protected $price = null;

    public static $table = 'order_product';
    public static $id_field = 'order_product_id';

    private  $_product = null;
    private $_photo = null;

    /**
     * @return null|\entities\Product
     */
    public function getProduct() {
        if ($this->_product === null) {
            $this->_product = Product::getById($this->product_id);
        }
        return $this->_product;
    }

    /**
     * @return null|\entities\Photo
     */
    public function getPhoto() {
        if ($this->_photo === null) {
            $this->_photo = Photo::getById($this->photo_id);
        }
        return $this->_photo;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function getOrderId()
    {
        return $this->order_id;
    }

    /**
     * @return int
     */
    public function getPhotoId()
    {
        return $this->photo_id;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    public function setAmount($value) {
        $this->amount = $value;
    }

    public function setPrice($value) {
        $this->price = $value;
    }

    public function setProduct($value) {
        $this->product_id = $value;
    }
    /**
     * @return int
     */
    public function getProductId()
    {
        return $this->product_id;
    }
}
?>