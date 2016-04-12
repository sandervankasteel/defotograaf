<?php
/**
 * Created by PhpStorm.
 * User: sander
 * Date: 12/10/14
 * Time: 2:26 PM
 */

namespace controllers;

use entities\Image;

class File_uploads extends Controller{

    private $jsonOuput;

    /**
     * The place where the AJAX request starts
     */

    public function index()
    {

        $previewFiles = [];

        try {

            foreach ($_FILES['files']['tmp_name'] as $file) {
                $this->image = new Image($file);
                $this->image->moveFile();
                $this->image->addToDatabase();

                array_push($previewFiles, $this->image->getFileName());
            }

        } catch(NotAnImage $e) {
            $error = [
                'error' => 'NotAnImage'
            ];
        }

        $this->jsonOuput = json_encode($previewFiles, JSON_UNESCAPED_SLASHES);

    }

    public function _finish()
    {
        echo $this->jsonOuput;
    }

} 