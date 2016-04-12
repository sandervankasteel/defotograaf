<?php
/**
 * Created by PhpStorm.
 * User: sander
 * Date: 21-11-14
 * Time: 14:58
 */

namespace controllers;

use entities\Image;
use exceptions\image\NotAnImage;
use exceptions\route\notFound;

class PhotoUpload extends Controller {

    private $image;

    public function index()
    {

        // Just here for debug reasons!
        var_dump(session_id());

        $this->view->setView("photo/upload");
        $this->view->setPageTitle("Foto's uploaden");
    }
}