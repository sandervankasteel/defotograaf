<?php
/**
 * Remco Schipper
 * Date: 04/12/14
 * Time: 12:19
 */

namespace controllers;

use entities\Order;
use entities\OrderStatus;
use exceptions\database\Prepare;
use services\Validator;

class Statuses extends Controller {
    private $validationFields = array('description' => array('empty' => false, 'length' => array(1, 255), 'message' => 'statuses.validation.description'));

    public function create() {
        if (isset($_POST['submit'])) {
            $validator = Validator::getInstance();
            $data = $validator->parsePost($_POST, $this->validationFields);

            if ($data['result'] === true) {
                $data['values']['active'] = (isset($_POST['active']) && $_POST['active'] == "actief");
                $method = new OrderStatus($data['values']);

                try {
                    $method->save();
                    $this->view->set('createResult', array('id' => $method->getId(), 'description' => $method->getDescription()));

                    $this->redirect('orderstatussen', 'index');
                }
                catch(Prepare $e) {
                    if ($e->getCode() === 1062) {
                        $this->view->set('createError', array('description' => $this->text('statuses.validation.description')));
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

    public function index() {
        $this->redirect('orderstatussen', 'overzicht', array('all'));
    }

    public function show($parameters) {
        $statuses = null;
        $view = $parameters['view'];

        if ($view === 'active') {
            $statuses = OrderStatus::getActive();
        }
        elseif ($view === 'inactive') {
            $statuses = OrderStatus::getInactive();
        }
        elseif ($view === 'all') {
            $statuses = OrderStatus::getAll();
        }
        else {
            $query = 'SELECT * FROM ' . OrderStatus::$table . ' WHERE description LIKE ?';
            $like = '%' . $view . '%';

            $statuses = OrderStatus::getByQuery($query, array($like), 's');
        }

        $this->view->set('showStatuses', ($statuses === null) ? array() : $statuses);
        $this->view->set('showFilter', $view);
    }

    public function edit($parameters) {
        /** @var \entities\OrderStatus $status */
        $status = OrderStatus::getBy('order_status_id', $parameters['id'], true);

        if ($status === null) {
            $this->redirect('instellingen', 'index');
        }
        else {
            if (isset($_POST['submit'])) {
                $validator = Validator::getInstance();
                $data = $validator->parsePost($_POST, $this->validationFields);

                if ($data['result'] === true) {
                    $status->setDescription($data['values']['description']);
                    $status->setActive((isset($_POST['active']) && $_POST['active'] == "actief"));

                    try {
                        $status->update();
                        $this->view->set('editResult', array('id' => $status->getId(), 'description' => $status->getDescription()));

                        $this->redirect('orderstatussen', 'index');
                    }
                    catch (Prepare $e) {
                        if ($e->getCode() === 1062) {
                            $this->view->set('editError', array('description' => $this->text('statuses.validation.description')));
                        }
                        else {
                            $this->view->set('editResult', false);
                        }
                    }
                }
                else {
                    $this->view->set('editError', $data['result']);
                }
            }

            $orders = Order::getBy('order_status', $status->getId());
            $this->view->set('viewOrders', $orders);
            $this->view->set('editStatus', $status);
        }
    }

    public function remove($parameters) {
        $status = OrderStatus::getById($parameters['id']);

        if ($status === null) {
            $this->redirect('orderstatussen', 'index');
        }
        else {
            if (count($status->getOrders()) > 0) {
                $this->view->set('removeResult', false);
            }
            else {
                try {
                    $status->delete();
                    $this->redirect('orderstatussen', 'index');
                }
                catch(Prepare $e) {
                    $this->view->set('removeResult', false);
                }
            }
        }
    }
}