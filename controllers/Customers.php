<?php
/**
 * Remco Schipper en Tim Oosterbroek
 * Date: 17/11/14
 * Time: 11:46
 */

namespace controllers;

use entities\Address;
use entities\EmailTemplate;
use entities\User;
use exceptions\authentication\UserNotFound;
use services\Database;
use services\Mail;
use services\Validator;
use services\Authentication;
use exceptions\database\Prepare;

class Customers extends Controller {
    private $validationFields = array('firstname' => array('empty' => false, 'length' => array(1, 255), 'message' => 'Het is verplicht uw voornaam in te vullen'),
    'lastname' => array('empty' => false, 'length' => array(1, 255), 'message' => 'Het is verplicht uw achternaam in te vullen'),
    'street' => array('empty' => false, 'length' => array(1, 255), 'message' => 'Moet een geldige straatnaam zijn'),
    'password' => array('empty' => false, 'length' => array(8, 300), 'message' => 'Het wachtwoord moet tussen de 8 en 300 tekens zijn'),
    'repeat_password' => array('empty' => false, 'equal' => 'password', 'message' => 'Wachtwoord herhaling moet overeen komen met het wachtwoord'),
    'house_number' => array('empty' => false, 'numeric' => true, 'length' => array(1, 45), 'message' => 'Uw huisnummer mag alleen cijfers bevatten'),
    'house_number_ad' => array('empty' => true),
    'zipcode' => array('empty' => false, 'length' => array(1, 45), 'message' => 'Uw postcode moet bestaan uit 4 cijfers en 2 letters zonder spatie'),
    'city' => array('empty' => false, 'length' => array(1, 45), 'message' => 'Het is verplicht uw woonplaats in te vullen'),
    'phonenumber' => array('empty' => false, 'phone' => true, 'message' => 'Uw telefoonnummer moet geldig zijn'),
    'email' => array('empty' => false, 'email' => true, 'length' => array(1, 255), 'message' => 'Een geldig e-mail adres is verplicht'));

    private function random(){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < 10; $i++) {
            $randstring = $characters[rand(0, strlen($characters))];
        }
        return $randstring;
    }

    public function forgot()
    {
        if (isset($_POST['submit'])) {
            $this->view->set('passForgot', $_POST);
            if (empty($_POST['email'])) {
                $this->view->set('passError', array('email' => 'Email is verplicht'));
            }
            else {
                /** @var \entities\User $user */
                $user = User::getBy('email', $_POST['email'], true);

                if ($user === null || ($user->hasPassword() === false && $user->getPasswordCode() === null)) {
                    $this->view->set('passError', array('email' => 'Email adress niet bekend'));
                }
                else {
                    $randomstring = md5($this->random());
                    $user->setPasswordCode($randomstring);

                    try {
                        $user->update(array('password'));

                        $template = EmailTemplate::getBy('action', 'account_1', true);
                        Mail::getInstance()->send($template, $user);
                    }
                    catch (Prepare $e) {
                        $this->view->set('updateResult', false);
                    }
                }
            }
        }
    }

    public function reset()
    {
        if (isset($_POST['submit'])){
            $values = $_POST;
            unset($values['password']);
            unset($values['repeat']);
            $this->view->set('resetValue', $values);

            if(empty($_POST['resetcode'])){
                $this->view->set('resetError', array('resetcode' => 'Code is verplicht'));
            }
            elseif(empty($_POST['password'])){
                $this->view->set('resetError', array('password' => 'Wachtwoord is verplicht'));
            }
            elseif(strlen($_POST['password']) < 8 || strlen($_POST['password'])> 300 ){
                $this->view->set('resetError', array('password' => 'Wachtwoord moet minimaal 8characters lang zijn'));
            }
            elseif($_POST['password'] !== $_POST['repeat']){
                $this->view->set('resetError', array('repeat' => 'Wachtwoorden komen niet overeen'));
            }
            else{
                /** @var \entities\User $user */
                $user = User::getBy('password_code',$_POST['resetcode'], true);

                if ($user === null){
                    $this->view->set('resetError', array('resetcode' => 'Code is niet bekend'));
                }
                else{
                    $user->setPassword($_POST['password']);

                    try{
                        $user->update(array('password_code'));

                        $this->view->set('editResult',true);
                    }
                    catch(Prepare $e) {
                        $this->view->set('editResult',false);
                    }
                }
            }
        }
    }

    public function show() {
        $this->view->set('customers', User::getAll());
    }

    public function update($parameters)
    {
        $user = Authentication::getInstance()->get();
        $customer = User::getById($parameters['id']);
        if ($user !== NULL && ($user->getRank() === 'admin' || $customer->getId() === $user->getId())) {


            $fields = $this->validationFields;

            unset($fields["password"]);
            unset($fields["repeat_password"]);


            $this->view->set('customer', $customer);

            if (isset($_POST['update'])) {
                $firstname = $_POST["firstname"];
                $lastname = $_POST["lastname"];
                $email = $_POST["email"];
                $phonenumber = $_POST["phonenumber"];
                $street = $_POST["street"];
                $house_number = $_POST["house_number"];
                $house_number_ad = $_POST["house_number_ad"];
                $zipcode = $_POST["zipcode"];
                $city = $_POST["city"];
                $password = $_POST["password"];
                $address = $customer->getAddress();
                $link = Database::get();

                $values = array();
                $validator = Validator::getInstance();

                foreach ($fields as $key => $value) {
                    $validator->setRulesForField($key, $value);

                    if (isset($_POST[$key])) {
                        $values[$key] = $_POST[$key];
                    }
                }

                $result = $validator->validate($values);

                if ($result === true) {
                    if (($_POST["street"] === $address->getStreet()) || ($_POST["house_number"] === $address->getHouseNumber()) || ($_POST["house_number_ad"] === $address->getHouseNumberAd())
                        || ($_POST["zipcode"] === $address->getZipCode()) || ($_POST["city"] === $address->getCity())
                    ) {
                        if (!empty($_POST["password"])) {
                            if ($_POST["password"] === $_POST["repeat_password"]) {
                                $stmt1 = mysqli_prepare($link, "UPDATE users SET firstname = ?, lastname = ?, email = ?, phonenumber =?, password =?
                              where address = ?");

                                mysqli_stmt_bind_param($stmt1, "sssisi", $firstname, $lastname, $email, $phonenumber, password_hash($password, PASSWORD_BCRYPT), $address->getId());
                                if (mysqli_stmt_execute($stmt1)) {
                                    $customer = User::getById($parameters['id']);
                                    $this->view->set('customer', $customer);
                                }
                            }
                        } else {
                            $stmt1 = mysqli_prepare($link, "UPDATE users SET firstname = ?, lastname = ?, email = ?, phonenumber = ?
                                                where address = ?");

                            mysqli_stmt_bind_param($stmt1, "sssii", $firstname, $lastname, $email, $phonenumber, $address->getId());
                            if (mysqli_stmt_execute($stmt1)) {
                                $customer = User::getById($parameters['id']);
                                $this->view->set('customer', $customer);

                            }
                        }
                        mysqli_stmt_free_result($stmt1);
                        mysqli_stmt_close($stmt1);
                    }

                } else {
                    $stmt = mysqli_prepare($link, "INSERT INTO addresses SET street = ?, house_number = ?, house_number_ad = ?, zipcode = ?, city = ?");
                    mysqli_stmt_bind_param($stmt, "sisss", $street, $house_number, $house_number_ad, $zipcode, $city);
                    if (mysqli_stmt_execute($stmt)) {

                        if (!empty($_POST["password"])) {
                            if ($_POST["password"] === $_POST["repeat_password"]) {
                                $stmt1 = mysqli_prepare($link, 'UPDATE users SET firstname = ?, lastname = ?, email = ?, phonenumber = ?, password = ?, address = ?
                                                            WHERE user_id = ?');

                                mysqli_stmt_bind_param($stmt1, "sssisii", $firstname, $lastname, $email, $phonenumber, password_hash($password, PASSWORD_BCRYPT), $address->getId(), $customer);
                                if (mysqli_stmt_execute($stmt1)) {
                                    $customer = User::getById($parameters['id']);
                                    $this->view->set('customer', $customer);
                                }
                            }
                        } else {
                            $stmt1 = mysqli_prepare($link, "UPDATE users SET firstname = ?, lastname = ?, email = ?, phonenumber = ?, address = ?
                                                    WHERE user_id = ?");
                            mysqli_stmt_bind_param($stmt1, "sssiii", $firstname, $lastname, $email, $phonenumber, $address->getId(), $customer->getId());
                            if (mysqli_stmt_execute($stmt1)) {
                                echo 'hoi';
                                $customer = User::getById($parameters['id']);
                                $this->view->set('customer', $customer);
                            }

                        }
                    } else {
                        $this->view->set('createError', $result);
                    }
                }
            }
        }
    }

    public function create() {
        if (isset($_POST['submit'])) {
            $validator = Validator::getInstance();

            $data = $validator->parsePost($_POST, $this->validationFields);
            $values = $data['values'];

            if ($data['result'] === true) {
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

                    $this->view->set('createResult', true);
                }
                catch (Prepare $e) {
                    if ($address->getId() !== null) {
                        $address->delete();
                    }

                    if ($e->getCode() === 1062) {
                        $this->view->set('createDouble', true);
                    }
                    else {
                        $this->view->set('createResult', false);
                    }

                    $this->view->set('createValues', $values);
                }

            } else {
                $this->view->set('createError', $data['result']);
                $this->view->set('createValues', $values);
            }
        }
    }
    public function login() {
        if (isset($_POST['login'])) {
            if (empty($_POST['email'])) {
                $this->view->set('loginError', array('email' => 'Email is verplicht'));
            }
            elseif (empty($_POST['password'])) {
                $this->view->set('loginError', array('password' => 'Password is verplicht'));
                $this->view->set('loginValues', array('email' => $_POST['email']));
            }
            else {
                try {
                    $auth = Authentication::getInstance();
                    $auth->login($_POST['email'], $_POST['password']);

                    if (!isset($_SESSION['redirect']) || $_SESSION['redirect'] === null){
                        $this->redirect('index', 'index');
                    }
                    else{
                        $target = json_decode($_SESSION['redirect']);
                        $_SESSION['redirect'] = null;

                        $this->redirect($target[0], $target[1], $target[2]);
                    }
                }
                catch (UserNotFound $e) {
                    $this->view->set('loginError', array('account' => 'Email en Password komen niet overeen'));
                    $this->view->set('loginValues', array('email' => $_POST['email']));
                }
            }
        }
    }
    public function logoff(){
        if ($_SESSION['user_id'] !== null) {
            $_SESSION['user_id'] = null;
        }

        $this->redirect('index', 'index');
    }
}
