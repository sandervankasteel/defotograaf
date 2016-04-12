<?php
/**
 * Created by PhpStorm.
 * User: sander
 * Date: 11/14/14
 * Time: 11:27 AM
 */

namespace controllers;

use services\View;
use services\Database;

class PhotoEdit extends Controller
{
    public function index()
    {
        $session = session_id();

        var_dump($session);

        $this->view->setView("PhotoEditOverview");
        $this->view->setPageTitle("Foto bewerking");
        $this->view->setNavigationBarTitle("Foto bewerking");

        $files = [];

        $dir = opendir("../public/mediaupload/" . $session . "/");

        while(($file = readdir($dir)) !== false)
        {
            if(strlen($file) > 3 && $file != "thumbs" && $file != "finished")
            {
                array_push($files, $session . "/" . $file);
            }
        }

        if (empty($files) || $dir == false)
        {
            header("Location: /PhotoUpload/");
        }

        $products = [
            [
                'id' => 0,
                'name' => '10x15 glans',
                'price' => 0.07,
                'height' => '15cm',
                'width' => '10cm'
            ],
            [
                'id' => 1,
                'name' => '10x15 mat',
                'price' => 0.07,
                'height' => '15cm',
                'width' => '10cm',
            ],
            [
                'id' => 2,
                'name' => '30x30 test',
                'price' => 0.07,
                'height' => '30cm',
                'width' => '30cm',
            ],
        ];

        $this->view->set('files', $files);
        $this->view->set('products', $products);

    }

    public function file_processing()
    {
        // First let's create the necesarry directories
        $dirName = getcwd() . "/mediaupload/" . session_id() . "/finished/";

        mkdir($dirName);

        mkdir($dirName . $_POST['size']);

        // Now we create a file opener so we can write to the file
        $fp = fopen($dirName . $_POST['size'] . "/" . $_POST['amount'] . "x " .$_POST['photoId'] . ".jpg", 'w+b');

        // Now let's handle the imageData
        $imageData = $_POST['imageData'];
        $filteredData = substr($imageData, strpos($imageData, ",")+1);
        $unencodedData=base64_decode($filteredData);

        // And write everything to the file and close the file handler
        fwrite($fp, $unencodedData);
        fclose($fp);
    }

}