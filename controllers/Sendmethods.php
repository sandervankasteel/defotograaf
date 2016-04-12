<?php
/**
 * Created by Roy Hendriks.
 * Date: 5-12-2014
 * Time: 14:15
 */

namespace controllers;

use entities\Order;
use entities\ShippingMethod;
use exceptions\database\Prepare;
use services\Validator;

class Sendmethods extends Controller {
    private $validationFields = array(
        'name' => array('empty' => false, 'length' => array(1, 100), 'message' => 'shippingmethods.validation.name')
    );

    public function index() {
        $this->redirect('verzendmethodes', 'overzicht', array('all'));
    }

    public function show($parameters) {
        $methods = null;
        $view = $parameters['view'];

        if ($view === 'active') {
            $methods = ShippingMethod::getActive();
        }
        elseif ($view === 'inactive') {
            $methods = ShippingMethod::getInactive();
        }
        elseif ($view === 'all') {
            $methods = ShippingMethod::getAll();
        }
        else {
            $query = 'SELECT * FROM ' . ShippingMethod::$table . ' WHERE name LIKE ?';
            $like = '%' . $view . '%';

            $methods = ShippingMethod::getByQuery($query, array($like), 's');
        }

        $this->view->set('showMethods', ($methods === null) ? array() : $methods);
        $this->view->set('showFilter', $view);
    }

    public function create() {
        if (isset($_POST['submit'])) {
            $validator = Validator::getInstance();
            $data = $validator->parsePost($_POST, $this->validationFields);

            if ($data['result'] === true) {
                $data['values']['is_active'] = (isset($_POST['active']) && $_POST['active'] == "actief");

                $method = new ShippingMethod($data['values']);

                try {
                    $method->save();
                    $this->view->set('createResult', array('id' => $method->getId(), 'name' => $method->getName()));

//                    $this->redirect('verzendmethodes', 'index');
                }
                catch(Prepare $e) {
                    if ($e->getCode() === 1062) {
                        $this->view->set('createError', array('name' => $this->text('shippingmethods.validation.double')));
                    }
                    else {
                        $this->view->set('createResult', false);
                    }
                }
            }
            else {
                $this->view->set('createError', $data['result']);
            }
        }
    }

    public function edit($parameters)
    {
        /** @var \entities\ShippingMethod $sendMethod */
        $sendMethod = ShippingMethod::getById($parameters['id']);

        if ($sendMethod === null) {
            $this->redirect('verzendmethodes', 'index');
        }
        else {
            if (isset($_POST['submit'])) {
                $validator = Validator::getInstance();
                $data = $validator->parsePost($_POST, $this->validationFields);

                if ($data['result'] === true) {
                    $values = $data['values'];

                    $sendMethod->setName($values['name']);
                    $sendMethod->setActive((isset($_POST['active']) && $_POST['active'] == "actief"));

                    try {
                        $sendMethod->update();
                        $this->view->set('editResult', array('id' => $sendMethod->getId(), 'name' => $sendMethod->getName()));

                        $this->redirect('verzendmethodes', 'index');
                    } catch (Prepare $e) {
                        if ($e->getCode() === 1062) {
                            $this->view->set('editError', array('name' => $this->text('shippingmethods.validation.double')));
                        } else {
                            $this->view->set('editResult', false);
                        }
                    }
                } else {
                    $this->view->set('editError', $data['result']);
                }
            }

            $this->view->set('viewOrders', $sendMethod->getOrders());
            $this->view->set('viewSendMethod', $sendMethod);
        }
    }

    public function remove($parameters) {
        $method = ShippingMethod::getById($parameters['id']);

        if ($method === null) {
            $this->redirect('verzendmethodes', 'index');
        }
        else {
            if (count($method->getOrders()) > 0) {
                $this->view->set('removeResult', false);
            }
            else {
                try {
                    $method->delete();
                    $this->redirect('verzendmethodes', 'index');
                }
                catch(Prepare $e) {
                    $this->view->set('removeResult', false);
                }
            }
        }
    }
}