<?php
/**
 * Remco Schipper
 * Date: 02/12/14
 * Time: 15:19
 */

namespace controllers;


use entities\DiscountCode;
use entities\Order;
use exceptions\database\Prepare;
use services\Validator;

class Coupons extends Controller {
    private $validationFields = array('code' => array('empty' => false, 'length' => array(1, 45), 'message' => 'coupons.validation.code'),
        'description' => array('empty' => false, 'length' => array(1, 45), 'message' => 'coupons.validation.description'),
        'valid_until' => array('empty' => false, 'datetime' => 'd-m-Y H:i', 'message' => 'coupons.validation.valid_until'),
        'amount' => array('empty' => true, 'int' => true, 'message' => 'coupons.validation.amount'));
    private $validationFixedAmount = array('empty' => false, 'numeric' => true, 'message' => 'coupons.validation.fixed_amount');
    private $validationPercentage = array('empty' => false, 'int' => true, 'message' => 'coupons.validation.percentage');

    public function create() {
        if (isset($_POST['submit'])) {
            $values = array();
            $validator = Validator::getInstance();

            if ($_POST['active'] === 'amount' || $_POST['active'] === 'percentage')  {
                $this->view->set('createActive', $_POST['active']);
                $fields = $this->validationFields;

                if ($_POST['active'] === 'amount') {
                    $fields['fixed_amount'] = $this->validationFixedAmount;
                }
                else {
                    $fields['percentage'] = $this->validationPercentage;
                }

                foreach ($fields as $key => $value) {
                    $validator->setRulesForField($key, $value);

                    if (isset($_POST[$key])) {
                        $values[$key] = $_POST[$key];
                    }
                }

                $result = $validator->validate($values);

                if ($result === true) {
                    $date = \DateTime::createFromFormat('d-m-Y H:i', $values['valid_until']);
                    $values['valid_until'] = $date->format('Y-m-d H:i:s');

                    if ($values['amount'] === '') {
                        $values['amount'] = null;
                    }

                    $coupon = new DiscountCode($values);

                    try {
                        $coupon->save();
                        $this->view->set('createResult', ['id' => $coupon->getId(), 'code' => $coupon->getCode()]);
                    }
                    catch (Prepare $e) {
                        if ($e->getCode() === 1062) {
                            $this->view->set('createError', array('code' => $this->text('coupons.validation.double')));
                        }
                        else {
                            $this->view->set('createResult', false);
                        }

                        $this->view->set('createValues', $values);
                    }
                }
                else {
                    $this->view->set('createError', $result);
                    $this->view->set('createValues', $values);
                }
            }
            else {
                $this->view->set('createResult', false);
            }
        }
    }

    public function remove($parameters) {
        $coupon = DiscountCode::getById($parameters['id']);

        if ($coupon === null) {
            $this->redirect('kortingscodes', 'index');
        }
        else {
            if (count($coupon->getOrders()) > 0) {
                $this->view->set('removeResult', false);
            }
            else {
                try {
                    $coupon->delete();
                    $this->redirect('kortingscodes', 'index');
                }
                catch(Prepare $e) {
                    $this->view->set('removeResult', false);
                }
            }
        }
    }

    public function index() {
        $this->redirect('kortingscodes', 'overzicht', array('all'));
    }

    public function show($parameters) {
        $coupons = null;
        $view = $parameters['view'];

        if ($view === 'active') {
            $coupons = DiscountCode::getActive();
        }
        elseif ($view === 'inactive') {
            $coupons = DiscountCode::getInactive();
        }
        elseif ($view === 'all') {
            $coupons = DiscountCode::getAll();
        }
        else {
            $query = 'SELECT * FROM ' . DiscountCode::$table . ' WHERE code LIKE ? OR description LIKE ?';
            $like = '%' . $view . '%';

            $coupons = DiscountCode::getByQuery($query, array($like, $like), 'ss');
        }

        $this->view->set('discountCodes', ($coupons === null) ? array() : $coupons);
        $this->view->set('showFilter', $view);
    }

    public function edit($parameters) {
        /** @var \entities\DiscountCode $coupon */
        $coupon = DiscountCode::getBy('discount_id', $parameters['id'], true);

        if ($coupon === null) {
            $this->redirect('kortingscodes', 'overzicht');
        }
        else {
            if (isset($_POST['submit'])) {
                $values = array();
                $validator = Validator::getInstance();

                $fields = $this->validationFields;

                if ($_POST['active'] === 'amount') {
                    $fields['fixed_amount'] = $this->validationFixedAmount;
                }
                else {
                    $fields['percentage'] = $this->validationPercentage;
                }

                foreach ($fields as $key => $value) {
                    $validator->setRulesForField($key, $value);

                    if (isset($_POST[$key])) {
                        $values[$key] = $_POST[$key];
                    }
                }

                $result = $validator->validate($values);

                if ($result === true) {
                    $date = \DateTime::createFromFormat('d-m-Y H:i', $values['valid_until']);
                    $values['valid_until'] = $date->format('Y-m-d H:i:s');

                    $null = array();
                    if (isset($values['fixed_amount'])) {
                        $coupon->setFixedAmount($values['fixed_amount']);
                        $coupon->setPercentage(null);
                        $null[] = 'percentage';
                    }
                    else {
                        $coupon->setPercentage($values['percentage']);
                        $coupon->setFixedAmount(null);
                        $null[] = 'fixed_amount';
                    }

                    $coupon->setCode($values['code']);
                    $coupon->setDescription($values['description']);
                    $coupon->setValidUntil($values['valid_until']);

                    if($values['amount'] === '') {
                        $null[] = 'amount';
                    }
                    else {
                        $coupon->setAmount($values['amount']);
                    }

                    try {
                        $coupon->update($null);
                        $this->view->set('editResult', ['id' => $coupon->getId(), 'code' => $coupon->getCode()]);
                    }
                    catch (Prepare $e) {
                        if ($e->getCode() === 1062) {
                            $this->view->set('editError', array('code' => $this->text('coupons.validation.double')));
                        }
                        else {
                            $this->view->set('editResult', false);
                        }
                    }
                }
                else {
                    $this->view->set('editError', $result);
                }
            }

            $this->view->set('viewCoupon', $coupon);
            $this->view->set('viewOrders', $coupon->getOrders());
        }
    }
}