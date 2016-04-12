<?php
/**
 * Remco Schipper
 * Date: 13/11/14
 * Time: 12:34
 */
namespace entities;

class User extends Entity{
    protected $user_id = null;
    protected $firstname = null;
    protected $lastname = null;
    protected $address = null;
    protected $phonenumber = null;
    protected $email = null;
    protected $password = null;
    protected $reg_date = null;
    protected $newsletter = null;
    protected $is_admin = null;
    protected $password_code = null;

    private $_address = null;

    public static $table = 'users';

    public function __construct($data = null) {
        $this->setRegDate(new \DateTime());

        parent::__construct($data);
    }

    /**
     * @param \DateTime $date
     */
    private  function setRegDate($date){
        $this->reg_date = $date->format('Y-m-d H:i:s');
    }

    /**
     * @param string $password
     */
    public function setPassword($password = null) {
        if ($password === null) {
            $this->password = null;
        }
        else {
            $this->password = password_hash($password, PASSWORD_BCRYPT);
        }
    }

    public function passwordMatches($password) {
        return password_verify($password, $this->password);
    }

    /**
     * @param Address $address
     */
    public function setAddress($address){
        $this->address = $address->getId();
    }

    public function getRank() {
        return ($this->is_admin == true) ? 'admin' : 'user';
    }

    /**
     * @return \entities\Address
     */
    public function getAddress()
    {
        if($this->_address === null) {
            $this->_address = Address::getById($this->address);
        }
        return $this->_address;
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phonenumber;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstname;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastname;
    }

    /**
     * @return null
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * @return null
     */
    public function getRegDate()
    {
        return $this->reg_date;
    }

    /**
     * @return bool
     */
    public function hasPassword()
    {
        if ($this->password === null){
            return false;
        }
        return true;
    }

    public function getPasswordCode(){
        return $this->password_code;
    }

    public function setPasswordCode($value){
        $this->password_code = $value;
    }
};