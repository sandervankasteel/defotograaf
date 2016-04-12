<?php
/**
 * Created by PhpStorm.
 * User: Albert Feijen
 * Date: 3-12-2014
 * Time: 21:24
 */

namespace controllers;

use entities\Product;
use services\Database;
use services\Validator;
use exceptions\database\Prepare;


class Articles extends Controller
{
    private $validationFields = array('article_name' => array('empty' => false, 'length' => array(1, 255), 'message' => 'Het is verplicht de product naam te geven'),
        'price' => array('empty' => false, 'numeric' => true, 'length' => array(1, 45), 'message' => 'Het is verplicht de prijs van het product te melden'),
        'dim_length' => array('empty' => false, 'numeric' => true, 'length' => array(1, 45), 'message' => 'Moet een geldige lengte zijn'),
        'dim_width' => array('empty' => false, 'numeric' => true, 'length' => array(1, 45), 'message' => 'Moet een geldige lengte zijn'),
        'shippingcosts' => array('empty' => false, 'numeric' => true, 'length' => array(1, 45), 'message' => 'Verzendkosten moet uit getallen bestaan'),
        'delivery_time' => array('empty' => false, 'length' => array(1, 45), 'message' => 'U moet een levertijd van 0 of meer dagen mee geven'),
        'is_active' => array('empty' => false, 'numeric' => true, 'length' => array(1, 1), 'message' => 'Er moet aangevinkt zijn of het artikel gebruikt kan worden in het bestelprocess'),
        'higher_shippingcosts' => array('empty' => false, 'numeric' => true, 'length' => array(1, 11), 'message' => 'U moet een levertijd van 0 of meer dagen mee geven'),
        'dropdownlocation' => array('empty' => false, 'numeric' => true, 'length' => array(1, 11), 'message' => 'U moet een levertijd van 0 of meer dagen mee geven'),
        'max_before_hshippingcosts' => array('empty' => false, 'numeric' => true, 'length' => array(1, 11), 'message' => 'U moet een levertijd van 0 of meer dagen mee geven'),);


    public function create()
    {
        if (isset($_POST['submit'])) {
            $values = array();
            $validator = Validator::getInstance();

            foreach ($this->validationFields as $key => $value) {
                $validator->setRulesForField($key, $value);

                if (isset($_POST[$key])) {
                    $values[$key] = $_POST[$key];
                }
            }

            $result = $validator->validate($values);

            if ($result === true) {
                $product = new Product($values);
                try {
                    $product->save();

                    $this->view->set('createResult', true);
                } catch (Prepare $e) {
                    if ($product->getId() !== null) {
                        $product->delete();
                    } else {
                        $this->view->set('createResult', false);
                    }

                    $this->view->set('createValues', $values);
                }

            } else {
                $this->view->set('createError', $result);
                $this->view->set('createValues', $values);
            }
        }
    }

    public function index()
    {
        $this->view->set('products', Product::getAll());
    }

    public function update($parameters)
    {

        $fields = $this->validationFields;

        $product = Product::getById($parameters['id']);
        $this->view->set('products', $product);

        if (isset($_POST['update'])) {
            $article_name = $_POST["article_name"];
            $price = $_POST["price"];
            $dim_length = $_POST["dim_length"];
            $dim_width = $_POST["dim_width"];
            $shippingcosts = $_POST["shippingcosts"];
            $delivery_time = $_POST["delivery_time"];
            $is_active = $_POST["is_active"];
            $dropdownlocation = $_POST["dropdownlocation"];
            $higher_shippingcosts = $_POST["higher_shippingcosts"];
            $max_before_hshippingcosts = $_POST["getMax_before_hshippingcosts"];
            $uid = $product->getId();
            $link = Database::get();

            // product_id, article_name, price, dim_length, dim_width, shippingcosts, delivery_time
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
                $stmt = mysqli_prepare($link, "UPDATE products SET article_name = ?, price = ?, dim_length = ?,
                dim_width = ?, shippingcosts = ?, delivery_time = ?, is_active = ?,  dropdownlocation = ?,
                higher_shippingcosts= ?, max_before_hshippingcosts = ?
                                where product_id = ?");
                mysqli_stmt_bind_param($stmt, "sdiidsiiiii", $article_name, $price, $dim_length, $dim_width, $shippingcosts,
                    $delivery_time, $is_active, $dropdownlocation, $higher_shippingcosts, $max_before_hshippingcosts, $uid);
                if (mysqli_stmt_execute($stmt)) {
                    $product = Product::getById($parameters['id']);
                    $this->view->set('products', $product);

                }
                mysqli_stmt_free_result($stmt);
                mysqli_stmt_close($stmt);
            } else {
                $this->view->set('createError', $result);
            }
        }
    }
    public function indexUpdate()
    {
        $updateRecordsArray = $_POST['recordsArray'];

            $link = Database::get();
            $listingCounter = 1;
            foreach ($updateRecordsArray as $recordIDValue) {

                $stmt = mysqli_prepare($link, "UPDATE products SET dropdownlocation = ?,
                                                where product_id = ?");
                mysqli_stmt_bind_param($stmt, "ii", $listingCounter, $recordIDValue);
                if (mysqli_stmt_execute($stmt)) {

                    $listingCounter = $listingCounter++;
                }
                mysqli_stmt_free_result($stmt);
                mysqli_stmt_close($stmt);


            }

        }

// product_id, article_name, price, dim_length, dim_width, shippingcosts, delivery_time


}