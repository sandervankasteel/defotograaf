<?php
/**
 * Created by PhpStorm.
 * User: sander
 * Date: 11/23/14
 * Time: 1:34 PM
 */

namespace entities;

use services\Database;
use exceptions\image\NotAnImage;

class Image extends Entity
{
    private $tmpName;
    private $fileName;
    private $imageType;
    private $imageHeight;
    private $imageWidth;
    private $directory;
    private $directoryThumbs;

    protected $upload_date;
    protected $effect;
    protected $stretch;

    public function __construct($fileName)
    {
        $this->checkImage($fileName);

        // Example output for getimagesize(); and Yes [0] and [1] are the width and height.. [3] returns those values in a string, primarily meant for use in a <img> element
        // array(7) {
        //     [0]=> int(751)
        //     [1]=> int(1200)
        //     [2]=> int(2)
        //     [3]=> string(25) "width="751" height="1200""
        //     ["bits"]=> int(8)
        //     ["channels"]=> int(3)
        //     ["mime"]=> string(10) "image/jpeg"
        // }

        // now we are gonna set some basic info about the image
        $imageInfo = getimagesize($fileName);

        $this->tmpName = $fileName;

        $this->imageType = $imageInfo['mime'];
        $this->imageWidth = $imageInfo[1];
        $this->imageHeight = $imageInfo[2];

        $this->createDirectories();
    }

    public function setUploadDate($date)
    {
        $this->upload_date = $date;
    }

    public function setEffect($effect)
    {
        $this->effect = $effect;
    }

    public function setStretch($stretch)
    {
        $this->stretch = $stretch;
    }

    private function checkImage($fileName)
    {
        $imageInfo = getimagesize($fileName);

        if ($imageInfo == FALSE)
        {
            throw new NotAnImage("This is not an image! Please do not do this EVER again... You dumbass");
        }
    }

    private function createDirectories()
    {
        if (!is_dir("mediaupload/" . session_id() . "/"))
        {
            $this->directory = "mediaupload/" . session_id() . "/";

            mkdir($this->directory, 0777);
        }

        $this->directory = "mediaupload/" . session_id() . "/";
    }

    private function getImageExtension()
    {
        if ($this->imageType == "image/jpeg")
        {
            return ".jpg";
        } elseif ($this->imageType == "image/png") {
            return ".png";
        }

        return null;
    }

    private function determineFileName()
    {
        $array = scandir($this->directory, SCANDIR_SORT_DESCENDING);

        $files = [];

        foreach ($array as $currentFile)
        {
            if ($currentFile != "." && $currentFile != ".." && $currentFile != "thumbs")
            {
                array_push($files, $currentFile);
            }
        }

        // Make sure the array is sorted from high to low
        sort($files, SORT_NUMERIC);

        if (count($files) != 0 )
        {
            $fileName = (int) substr($files[count($files) - 1], 0, -4);
            $fileName++;
            $this->fileName = $fileName;
        } else {
            $this->fileName = 1;
        }

    }

    // TODO WRITE THIS FUNCTION!
    public function addToDatabase()
    {

    }

    public function moveFile()
    {
        if($this->imageType == "image/jpeg")
        {
            $image = imagecreatefromjpeg($this->tmpName);

            $this->determineFileName();
            $name = $this->directory . $this->fileName . $this->getImageExtension();

            imagejpeg($image, $name);

        } elseif ($this->imageType == "image/png")
        {
            $image = imagecreatefrompng($this->tmpName);

            $this->determineFileName();
            $name = $this->directory . $this->fileName . $this->getImageExtension();

            imagepng($image, $name);
        }
    }

    public function getThumbFile()
    {
        return $this->directoryThumbs . "" . $this->fileName . $this->getImageExtension();
    }

    public function getFileName()
    {
        return $this->directory . $this->fileName . $this->getImageExtension();
    }

} 