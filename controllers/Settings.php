<?php
/**
 * Remco Schipper
 * Date: 02/12/14
 * Time: 11:23
 */

namespace controllers;


use entities\Configuration;
use entities\DiscountCode;
use entities\PaymentMethod;
use entities\ShippingMethod;
use entities\OrderStatus;
use entities\StartingCost;
use exceptions\database\Prepare;
use services\Validator;

class Settings extends Controller {
    private $validationFields = array(
        'price' => array('empty' => false, 'numeric' => true, 'message' => 'settings.validation.price'),
        'btw' => array('empty' => false, 'int' => true, 'message' => 'settings.validation.btw'),
        'iban' => array('empty' => false, 'length' => 18, 'message' => 'settings.validation.iban'));

    public function index() {
        $btwPercentage = Configuration::getById('BTW');
        $ibanNumber = Configuration::getById('IBAN');

        if ($btwPercentage === null) {
            $btwPercentage = new Configuration(array('configuration_key' => 'BTW', 'configuration_value' => '21'));
            $btwPercentage->save();
        }
        if ($ibanNumber === null) {
            $ibanNumber = new Configuration(array('configuration_key' => 'IBAN', 'configuration_value' => '123456789123456789'));
            $ibanNumber->save();
        }

        $this->view->set('discountCodes', DiscountCode::getActive());
        $this->view->set('shippingMethods', ShippingMethod::getActive());
        $this->view->set('paymentMethods', PaymentMethod::getActive());
        $this->view->set('orderStatuses', OrderStatus::getActive());

        $this->view->set('startingCost', StartingCost::getActive());
        $this->view->set('btwPercentage', $btwPercentage);
        $this->view->set('ibanNumber', $ibanNumber);
    }

    public function edit() {
        $startingCost = StartingCost::getActive();
        $btwPercentage = Configuration::getById('BTW');
        $ibanNumber = Configuration::getById('IBAN');

        if (isset($_POST['submit'])) {
            $validator = Validator::getInstance();
            $data = $validator->parsePost($_POST, $this->validationFields);

            if ($data['result'] === true) {
                $values = $data['values'];

                if ($values['price'] != $startingCost->getPrice()) {
                    $startingCost = new StartingCost(array('price' => $values['price']));

                    try {
                        $startingCost->save();
                        $this->view->set('editStartingCost', true);
                    }
                    catch (Prepare $e) {
                        $this->view->set('editStartingCost', false);
                    }
                }

                if ($values['btw'] !== $btwPercentage->getValue()) {
                    $btwPercentage->setValue($values['btw']);

                    try {
                        $btwPercentage->update();
                        $this->view->set('editBtwPercentage', true);
                    }
                    catch (Prepare $e) {
                        $this->view->set('editBtwPercentage', false);
                    }
                }

                if ($values['iban'] !== $ibanNumber->getValue()) {
                    $ibanNumber->setValue($values['iban']);

                    try {
                        $ibanNumber->update();
                        $this->view->set('editIbanNumber', true);
                    }
                    catch (Prepare $e) {
                        $this->view->set('editIbanNumber', false);
                    }
                }
            }
            else {
                $this->view->set('editError', $data['result']);
            }
        }

        $this->view->set('startingCost', $startingCost);
        $this->view->set('btwPercentage', $btwPercentage);
        $this->view->set('ibanNumber', $ibanNumber);
    }
}