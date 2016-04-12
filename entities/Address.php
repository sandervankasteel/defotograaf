<?php
/**
 * Remco Schipper en Tim Oosterbroek
 * Date: 17/11/14
 * Time: 13:27
 */

namespace entities;


class Address  extends Entity{
    protected $address_id = null;
    protected $street = null;
    protected $house_number = null;
    protected $house_number_ad = null;
    protected $zipcode = null;
    protected $city = null;

    public static $table = 'addresses';

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return number
     */
    public function getHouseNumber()
    {
        return $this->house_number;
    }

    /**
     * @return null
     */
    public function getHouseNumberAd()
    {
        return $this->house_number_ad;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipcode;
    }


}


