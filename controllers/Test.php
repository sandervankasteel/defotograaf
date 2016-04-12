<?php
/**
 * Created by PhpStorm.
 * User: sander
 * Date: 11/14/14
 * Time: 11:51 AM
 */
namespace controllers;

use services\View;

class Test
{
    public function index()
    {

        $session = "v10kpn9oaoq4jnorp2rpbhp521";

        $phoEdit = new View();
        $phoEdit->setView("PhotoEditOverview");

        $files = [];

        $dir = opendir("../public/mediaupload/" . $session . "/");

        while(($file = readdir($dir)) !== false)
        {
            if(strlen($file) > 3 && $file != "thumbs")
            {
                array_push($files, $session . "/" . $file);
            }
        }

        if (empty($files) || $dir == false)
        {
            header("Location: /PhotoUpload/");
        }

        $phoEdit->set('files', $files);
        $phoEdit->render();
    }

    public function test2()
    {

    }
}