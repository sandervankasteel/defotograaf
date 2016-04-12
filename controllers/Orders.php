<?php
/**
 * Joey Smit
 * Date: 26/11/14
 * Time: 12:16
 */

namespace controllers;

use entities\Configuration;
use entities\DiscountCode;
use entities\EmailTemplate;
use entities\Order;
use entities\OrderStatus;
use entities\PaymentMethod;
use entities\Product;
use entities\ShippingMethod;
use entities\StartingCost;
use entities\User;
use exceptions\database\Prepare;
use services\Authentication;
use services\Mail;

class Orders extends Controller {
    public function index() {

    }
    public function create($parameters) {
        $step = $parameters['step'];
        $this->setView(array('orders', 'step' . $step));

        $auth = Authentication::getInstance();

        if ($step == 1 || $step == 2) {
            if ($auth->get() === null) {
                if ($step == 1) {
                    $auth->auth('bestelling', 'plaatsen', array('3'), false);
                }
                else {
                    if (isset($_POST['submit'])) {
                        $result = Authentication::getInstance()->signup($_POST);

                        if ($result === true) {
                            $_SESSION['order_user'] = $result['user']->getId();
                            $this->redirect('bestelling', 'plaatsen', array(3));
                        }
                        else {
                            if ($result['error'] === 'double') {

                            }
                            elseif ($result['error'] === 'sql') {
                                $this->view->set('createResult', false);
                            }
                            else {
                                $this->view->set('createError', $result['data']);
                            }

                            $this->view->set('createValues', $result['values']);
                        }
                    }
                }
            }
            else {
                $this->redirect('bestelling', 'plaatsen', array('3'));
            }
        }
        else if ($step == 3) {
            if ($auth->get() !== null || isset($_SESSION['order_user'])) {
                $user = $auth->get();
                if ($user === null) {
                    $user = User::getById($_SESSION['order_user']);
                }

                $order = null;
                if (isset($_SESSION['order_id'])) {
//                    $order = Order::getById($_SESSION['order_id']);
                }
                else {
                    $order = new Order(array(
                        'user_id' => $user->getId(),
                        'shipping_address' => $user->getAddress()->getId(),
                        'starting_costs' => StartingCost::getActive()->getId(),
                        'order_status' => OrderStatus::getById(1)->getId()
                    ));

                    $order->save();
                    $_SESSION['order_id'] = $order->getId();
                }

                $products = Product::getAll();
                $jsProducts = array();

                foreach($products as $product) {
                    $jsProducts[$product->getId()] = array(
                        'id' => $product->getId(),
                        'name' => $product->getName(),
                        'ppi' => $product->getPpi(),
                        'width' => $product->getDimWidth(),
                        'height' => $product->getDimLength()
                    );
                }

//                $lines = $order->getLines();
//                $jsLines = array();
//                if (count($lines) > 0) {
//
//                }

                $this->view->set('viewProducts', $products);
                $this->view->set('jsProducts', $jsProducts);
                //$this->view->set('jsLines', $jsLines);
            }
            else {
                $this->redirect('bestelling', 'plaatsen', array('1'));
            }
        }
        else if ($step == 4) {
            if ($auth->get() !== null || isset($_SESSION['order_user'])) {
                $user = $auth->get();

                if ($user === null) {
                    $user = User::getById($_SESSION['order_user']);
                }

                if (isset($_SESSION['order_id'])) {
                    $order = Order::getById($_SESSION['order_id']);

                    if (isset($_POST['sbmCoupon'])) {
                        /** @var \entities\DiscountCode $coupon */
                        $coupon = DiscountCode::getBy('code', $_POST['coupon'], true);

                        if ($coupon === null) {
                            $this->view->set('couponError', 1);
                        }
                        else {
                            $now = new \DateTime();

                            if ($coupon->getValidUntil() < $now) {
                                $this->view->set('couponError', 2);
                            }
                            else {
                                $query = 'SELECT * FROM ' . Order::$table . ' WHERE discount_code_id=? and user_id=?';

                                $userCoupon = Order::getByQuery($query, array($coupon->getId(), $user->getId()), 'ii');

                                if ($userCoupon === null || count($userCoupon) === 0) {
                                    $continue = true;

                                    if ($coupon->getAmount() !== null) {
                                        $coupons = Order::getBy('discount_code_id', $coupon->getId(), false);

                                        if ($coupons !== null && count($coupons) >= $coupon->getAmount()) {
                                            $this->view->set('couponError', 4);
                                            $continue = false;
                                        }
                                    }

                                    if ($continue) {
                                        $order->setDiscountCode($coupon->getId());

                                        try {
                                            $order->update();
                                            $this->view->set('couponResult', true);
                                        }
                                        catch(Prepare $e) {
                                            $this->view->set('couponResult', false);
                                        }
                                    }
                                }
                                else {
                                    $this->view->set('couponError', 3);
                                }
                            }
                        }
                    }

                    $this->view->set('viewOrder', $order);
                    $this->view->set('viewLines', $order->getLines());
                    $this->view->set('shippingMethods', ShippingMethod::getActive());
                    $this->view->set('paymentMethods', PaymentMethod::getActive());
                    $this->view->set('btwPercentage', Configuration::getById('BTW'));
                }
                else {
                    $this->redirect('bestelling', 'plaatsen', array('1'));
                }
            }
            else {
                $this->redirect('bestelling', 'plaatsen', array('1'));
            }
        }
        else {
            $this->redirect('bestelling', 'plaatsen', array('1'));
        }
    }
    public function view($parameters) {
        $order = Order::getById($parameters['id']);
        $user = Authentication::getInstance()->get();

        if ($user !== null && $order !== NULL && ($user->getRank() === 'admin' || $order->getUser()->getId() === $user->getId())) {
            $btwPercentage = Configuration::getById('BTW');

            if(isset($_POST['submit']) && isset($_POST['statusUpdate']))  {
                $new_status = $_POST['statusUpdate'];

                $status = OrderStatus::getById($new_status);
                if ($status === null) {
                    $this->view->set('viewResult', false);
                } else {
                    $order->setStatus($status);
                    $order->update();

                    $mail = Mail::getInstance();
                    /** @var \entities\EmailTemplate $template */
                    $template = EmailTemplate::getBy('action', 'status_' . $status->getId(), true);
                    if ($template !== NULL) {
                        $mail->send($template, $order->getUser(), array('OrderStatus' => $status, 'Order' => $order));
                    }
                    $this->view->set('viewResult', true);
                }
            }+
            $this->view->set('btwPercentage', $btwPercentage);

            $this->view->set('statuses', OrderStatus::getAll());
            $this->view->set('order', $order);
        }
    }
}