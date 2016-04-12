<?php
/**
 * Remco Schipper
 * Date: 11/12/14
 * Time: 15:17
 */

namespace controllers;


use entities\PaymentMethod;
use exceptions\database\Prepare;
use services\Validator;

class Paymentmethods extends Controller {
    private $validationFields = array(
        'name' => array('empty' => false, 'length' => array(1, 100), 'message' => 'paymentmethods.validation.name'),
        'description' => array('empty' => false, 'length' => array(1, 9000), 'message' => 'paymentmethods.validation.description'),
    );

    public function index() {
        $this->redirect('betaalmethodes', 'overzicht', array('all'));
    }

    public function show($parameters) {
        $methods = null;
        $view = $parameters['view'];

        if ($view === 'active') {
            $methods = PaymentMethod::getActive();
        }
        elseif ($view === 'inactive') {
            $methods = PaymentMethod::getInactive();
        }
        elseif ($view === 'all') {
            $methods = PaymentMethod::getAll();
        }
        else {
            $query = 'SELECT * FROM ' . PaymentMethod::$table . ' WHERE name LIKE ? OR description LIKE ?';
            $like = '%' . $view . '%';

            $methods = PaymentMethod::getByQuery($query, array($like, $like), 'ss');
        }

        $this->view->set('showMethods', ($methods === null) ? array() : $methods);
        $this->view->set('showFilter', $view);
    }

    public function create() {
        if (isset($_POST['submit'])) {
            $validator = Validator::getInstance();
            $data = $validator->parsePost($_POST, $this->validationFields);

            if ($data['result'] === true) {
                $data['values']['is_active'] = (isset($_POST['active']) && $_POST['active'] == "actief");;
                $method = new PaymentMethod($data['values']);

                try {
                    $method->save();
                    $this->view->set('createResult', array('id' => $method->getId(), 'name' => $method->getName()));

                    $this->redirect('betaalmethodes', 'index');
                }
                catch(Prepare $e) {
                    if ($e->getCode() === 1062) {
                        $this->view->set('createError', array('name' => $this->text('paymentmethods.validation.double')));
                    }
                    else {
                        $this->view->set('createResult', false);
                    }
                }
            }
            else {
                $this->view->set('createValues', $data['values']);
                $this->view->set('createError', $data['result']);
            }
        }
    }

    public function edit($parameters)
    {
        /** @var \entities\PaymentMethod $paymentMethod */
        $paymentMethod = PaymentMethod::getById($parameters['id']);

        if ($paymentMethod === null) {
            $this->redirect('betaalmethodes', 'index');
        }
        else {
            if (isset($_POST['submit'])) {
                $validator = Validator::getInstance();
                $data = $validator->parsePost($_POST, $this->validationFields);

                if ($data['result'] === true) {
                    $values = $data['values'];

                    $paymentMethod->setName($values['name']);
                    $paymentMethod->setDescription($values['description']);
                    $paymentMethod->setActive((isset($_POST['active']) && $_POST['active'] == "actief"));

                    try {
                        $paymentMethod->update();
                        $this->view->set('editResult', array('id' => $paymentMethod->getId(), 'name' => $paymentMethod->getName()));

                        $this->redirect('betaalmethodes', 'index');
                    } catch (Prepare $e) {
                        if ($e->getCode() === 1062) {
                            $this->view->set('editError', array('name' => $this->text('paymentmethods.validation.double')));
                        } else {
                            $this->view->set('editResult', false);
                        }
                    }
                } else {
                    $this->view->set('editError', $data['result']);
                }
            }

            $this->view->set('viewOrders', $paymentMethod->getOrders());
            $this->view->set('viewPaymentMethod', $paymentMethod);
        }
    }

    public function remove($parameters) {
        $method = PaymentMethod::getById($parameters['id']);

        if ($method === null) {
            $this->redirect('betaalmethodes', 'index');
        }
        else {
            if (count($method->getOrders()) > 0) {
                $this->view->set('removeResult', false);
            }
            else {
                try {
                    $method->delete();
                    $this->redirect('betaalmethodes', 'index');
                }
                catch(Prepare $e) {
                    $this->view->set('removeResult', false);
                }
            }
        }
    }
} 