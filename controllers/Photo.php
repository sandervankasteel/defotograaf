<?php
/**
 * Remco Schipper
 * Date: 18/11/14
 * Time: 11:12
 */

namespace controllers;


use entities\OrderProduct;
use entities\PhotoFillFit;
use entities\Product;
use entities\User;
use entities\Photo as ePhoto;
use exceptions\database\Prepare;
use exceptions\upload\FileIsNotAnImage;
use services\Authentication;
use config\Upload as config;

class Photo extends Controller {
    private $response = array();
    private $json = true;

    private function createSessionDir() {
        $hash = md5(time() . rand(1, 9999999999));
        $path = implode(DIRECTORY_SEPARATOR, array(config::getUploadDir(), $hash));

        if (file_exists($path)) {
            $hash = $this->createSessionDir();
        }

        if (mkdir($path)) {
            return array($path, $hash);
        }

        return null;
    }

    private function getPath($sub) {
        $path = null;
        if (isset($_SESSION['order_hash'])) {
            $path = implode(DIRECTORY_SEPARATOR, array(config::getUploadDir(), $_SESSION['order_hash']));

            if (!file_exists($path)) {
                if (!mkdir($path)) {
                    throw new \CouldNotCreateDirectory();
                }
            }
        }
        else {
            $data = $this->createSessionDir();

            if ($data === null) {
                throw new \CouldNotCreateDirectory();
            }
            else {
                $_SESSION['order_hash'] = $data[1];
            }

            $path = $data[0];
        }

        $path = implode(DIRECTORY_SEPARATOR, array($path, $sub));

        if (!file_exists($path)) {
            if (!mkdir($path)) {
                throw new \CouldNotCreateDirectory();
            }
        }

        return $path;
    }

    public function edit($parameters) {
        $photo = ePhoto::getById($parameters['id']);

        if ($photo === null) {
            $this->response['error'] = 'does_not_exist';
        }
        else {
            $user = Authentication::getInstance()->get();

            if ($user === null) {
                $user = User::getById($_SESSION['order_user']);
            }

            if ($user !== null && ($photo->getUser()->getId() === $user->getId() || $user->getRank() === 'admin')) {
                $path = $this->getPath('final');
                $nPath = $path . DIRECTORY_SEPARATOR . $photo->getFileName();

                if ((isset($_POST['image']) && isset($_POST['effect_id']) && isset($_POST['stretch_id']))) {
                    $null = array();

                    if (file_exists($nPath)) {
                        unlink($nPath);
                    }

                    $data = str_replace('data:image/png;base64,', '', $_POST['image']);
                    $data = str_replace(' ', '+', $data);

                    file_put_contents($nPath, base64_decode($data));

                    $photo->setStretch($_POST['stretch_id']);

                    if ($_POST['effect_id'] !== 'none') {
                        $photo->setEffect($_POST['effect_id']);
                    }
                    else {
                        $null[] = 'effect';
                    }

                    $photo->update($null);
                }

                if(isset($_POST['amount']) || isset($_POST['product_id'])) {
                    $amount = $_POST['amount'];

                    /** @var \entities\OrderProduct $orderLine */
                    $orderLine = OrderProduct::getBy('photo_id', $photo->getId(), true);

                    if ($orderLine->getAmount() != $amount || $orderLine->getProduct()->getId() != $product_id) {
                        $product = null;

                        if (isset($_POST['product_id'])) {
                            $product = ($orderLine->getProduct()->getId() != $_POST['product_id']) ? Product::getById($_POST['product_id']) : $orderLine->getProduct();
                        }

                        if($product === null) {
                            $product = $orderLine->getProduct();
                        }

                        $amount = ($orderLine->getAmount() != $amount) ? $amount : $orderLine->getAmount();

                        if ($product !== null && $amount > 0) {
                            $orderLine->setAmount($amount);
                            $orderLine->setProduct($product->getId());
                            $orderLine->setPrice($amount * $product->getPrice());

                            $orderLine->update();
                        }
                    }
                }
            }
            else {
                $this->response['error'] = 'unauthorized';
            }
        }
    }

    public function view($parameters) {
        $photo = ePhoto::getById($parameters['id']);

        if ($photo === null) {
            $this->response['error'] = 'does_not_exist';
        }
        else {
            $user = Authentication::getInstance()->get();

            if ($user === null) {
                $user = User::getById($_SESSION['order_user']);
            }

            if ($user !== null && ($photo->getUser()->getId() === $user->getId() || $user->getRank() === 'admin')) {
                $type = ($parameters['type'] === 'original') ? 'original' : 'final';

                $path = implode(DIRECTORY_SEPARATOR, array(config::getUploadDir(), $photo->getHash(), $type, $photo->getFileName()));
                $info = getimagesize($path);

                if ($info === false) {
                    $this->response['error'] = 'does_not_exist';
                }
                else {
                    $fp = fopen($path, 'rb');

                    header("Content-Type: " . $info['mime']);
                    header("Content-Length: " . filesize($path));
                    header('Cache-Control: max-age=37739520, public');

                    fpassthru($fp);
                }
            }
            else {
                $this->response['error'] = 'unauthorized';
            }
        }
    }

    public function upload() {
        $user = Authentication::getInstance()->get();

        if (count($_FILES['files']['name']) === 1) {
            if ($user !== null || isset($_SESSION['order_user'])) {
                if ($user === null) {
                    $user = User::getById($_SESSION['order_user']);
                }

                $path = $this->getPath('original');

                $file = array(
                    'tmp_name' => $_FILES['files']['tmp_name'][0],
                    'name' => $_FILES['files']['name'][0]
                );
                $info = getimagesize($file['tmp_name']);

                if ($info === false) {
                    throw new FileIsNotAnImage();
                }
                else {
                    $extension = end(explode('.', $file['name']));
                    $name = time();
                    $filename = $name . '.' . $extension;

                    move_uploaded_file($file['tmp_name'], implode(DIRECTORY_SEPARATOR, array($path, $filename)));

                    $stretch = PhotoFillFit::getBy('stretch_name', 'fit-in', true);

                    $photo = new ePhoto(array(
                            'filename' => $filename,
                            'stretch' => $stretch->getId(),
                            'hash' => $_SESSION['order_hash'],
                            'user_id' => $user->getId(),
                        )
                    );

                    try {
                        $photo->save();
                        $product = Product::getById(1);

                        $orderLine = new OrderProduct(array(
                            'order_id' => $_SESSION['order_id'],
                            'product_id' => $product->getId(),
                            'photo_id' => $photo->getId(),
                            'amount' => 1,
                            'price' => $product->getPrice()
                        ));

                        $orderLine->save();


                        $this->response['id'] = $photo->getId();
                        $this->response['name'] = $name;
                        $this->response['file_name'] = $filename;
                        $this->response['path'] = '/foto/bekijken/' . $photo->getId() . '/original';
                    }
                    catch(Prepare $e) {
                        $this->response['error'] = 'database_error';
                        $this->setStatus(500);
                    }
                }
            }
            else {
                $this->response['error'] = 'unauthorized';
                $this->setStatus(401);
            }
        }
        else {
            $this->response['error'] = 'invalid_amount_of_files';
            $this->setStatus(406);
        }
    }

    public function _finish() {
        if ($this->json) {
            http_response_code($this->status);
            echo json_encode($this->response);
        }
    }
}