<?php
/** Joey Smit */
namespace entities;


class Product extends Entity
{
    protected $product_id = null;
    protected $article_name = null;
    protected $price = null;
    protected $dim_length = null;
    protected $dim_width = null;
    protected $ppi = null;
    protected $shippingcosts = null;
    protected $delivery_time = null;
    protected $is_active = null;
    protected $dropdownlocation = null;
    protected $higher_shippingcosts = null;
    protected $max_before_shippingcosts = null;

    public static $table = 'products';
    public static $id_field = 'product_id';

    public function __construct($data = null)
    {

        parent::__construct($data);
    }

    public function getPpi() {
        return $this->ppi;
    }
    /**
     * @return int
     */
    public function getDeliveryTime()
    {
        return $this->delivery_time;
    }

    /**
     * @return int
     */
    public function getDimWidth()
    {
        return $this->dim_width;
    }

    /**
     * @return int
     */
    public function getDimLength()
    {
        return $this->dim_length;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->article_name;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getShippingCosts()
    {
        return $this->shippingcosts;
    }


    /**
     * @return int
     */
    public function isActive()
    {
        return $this->is_active;
    }

    /**
     * @return int
     */
    public function getHigherShippingCosts()
    {
        return $this->higher_shippingcosts;
    }


    /**
     * @return int
     */
    public function getDropdownLocation()
    {
        return $this->dropdownlocation;
    }

    /**
     * @return int
     */

    public function getMaxBeforeShippingCosts()
    {
        return $this->max_before_shippingcosts;
    }
}

?>