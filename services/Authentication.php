<?php
/**
 * Remco Schipper / Roy Hendriks
 * Date: 20/11/14
 * Time: 11:53
 */

namespace services;

use config\Routes;
use entities\Address;
use entities\User;
use exceptions\authentication\UserNotFound;
use exceptions\database\Prepare;

class Authentication extends Service {
    private $validationFields = array('firstname' => array('empty' => false, 'length' => array(1, 255), 'message' => 'Het is verplicht uw voornaam in te vullen'),
        'lastname' => array('empty' => false, 'length' => array(1, 255), 'message' => 'Het is verplicht uw achternaam in te vullen'),
        'street' => array('empty' => false, 'length' => array(1, 255), 'message' => 'Moet een geldige straatnaam zijn'),
        'password' => array('empty' => true, 'length' => array(8, 300), 'message' => 'Het wachtwoord moet tussen de 8 en 300 tekens zijn'),
        'repeat_password' => array('equal' => 'password', 'message' => 'Het wachtwoord moet overeen komen'),
        'house_number' => array('empty' => false, 'numeric' => true, 'length' => array(1, 45), 'message' => 'Uw huisnummer mag alleen cijfers bevatten'),
        'house_number_ad' => array('empty' => true),
        'zipcode' => array('empty' => false, 'length' => array(1, 45), 'message' => 'Uw postcode moet bestaan uit 4 cijfers en 2 letters zonder spatie'),
        'city' => array('empty' => false, 'length' => array(1, 45), 'message' => 'Het is verplicht uw woonplaats in te vullen'),
        'phonenumber' => array('empty' => false, 'phone' => true, 'message' => 'Uw telefoonnummer moet geldig zijn'),
        'email' => array('empty' => false, 'email' => true, 'length' => array(1, 255), 'message' => 'Een geldig e-mail adres is verplicht'));

    /** @var null|\entities\User */
    private $account = null;

    /**
     * Assigns the current user id to the session
     * @param \entities\User $user
     */
    private function session($user) {
       $_SESSION['user_id'] = $user->getId();
   }

    /**
     * Fetches the current user from the session and database
     * @return \entities\User
     */
    public function get() {
        if ($this->account === null){
            if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])){
                $this->account = User::getById($_SESSION['user_id']);
            }
        }
        return $this->account;

    }

    /**
     * Checks if the user is allowed to visit a page
     * @param array $route
     * @return bool
     */
    public function check($route) {
        if (array_key_exists('access', $route['config'])) {
            $account = $this->get();
            $list = $route['config']['access'];

            return ($account === null) ? in_array('guest', $list) : in_array($account->getRank(), $list);
        }
        else {
            return true;
        }
    }

    /**
     * Handles the registration of an user
     * @param array $data
     * @return array|bool
     */
    public function signup($data) {
        $validator = Validator::getInstance();

        $data = $validator->parsePost($data, $this->validationFields);
        $return = array('result' => false);

        if ($data['result'] === true) {
            $values = $data['values'];

            $address = new Address($values);

            try {
                $address->save();

                $customer = new User($values);
                $customer->setAddress($address);

                if (isset($values['password']) && !empty($values['password'])) {
                    $customer->setPassword($values['password']);
                }
                else {
                    $customer->setPassword(null);
                }

                $customer->save();

                $return['result'] = true;
                $return['user'] = $customer;
            }
            catch (Prepare $e) {
                if ($address->getId() !== null) {
                    $address->delete();
                }

                if ($e->getCode() === 1062) {
                    $return['error'] = 'double';
                }
                else {
                    $return['error'] = 'sql';
                }
            }

        }
        else {
            $return['error'] = 'validation';
            $return['data'] = $data['result'];
        }

        $return['values'] = $data['values'];

        return $return;
    }

    /**
     * Handles the login process
     * @param string $email
     * @param string $password
     * @throws UserNotFound
     * @throws \exceptions\database\Prepare
     */
    public function login($email, $password) {
        if (isset($email) && isset($password)) {
            /** @var \entities\User $user */
            $user = User::getBy('email', $email, true);

            if ($user === null) {
                throw new UserNotFound();
            }
            else {
                if ($user->passwordMatches($password)) {
                    $this->account = $user;
                    $this->session($this->account);
                    return $this->account;
                }
                else {
                    throw new UserNotFound();
                }
            }
        }
        else {
            throw new UserNotFound();
        }
    }

    /**
     * Starts the login procedure and redirects back after a successfull login
     * @param string $controller
     * @param string $action
     * @param array $params
     * @param bool $redirect
     */
    public function auth($controller, $action, $params = array(), $redirect = true) {
        $target = array($controller, $action, $params);
        $_SESSION['redirect'] = json_encode($target);

        if ($redirect === true) {
            header('Location: /gebruikers/inloggen');
        }
    }
}