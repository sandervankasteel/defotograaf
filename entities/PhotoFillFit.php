<?php
/**
 * Created by PhpStorm.
 * User: Joey
 * Date: 4-12-2014
 * Time: 18:36
 */

namespace entities;

class PhotoFillFit extends Entity
{
    protected $photo_stretch_id = null;
    protected $stretch_name = null;

    public static $table = 'photo_stretch';
    public static $id_field = 'photo_stretch_id';

    /**
     * @return string
     */
    public function getName()
    {
        return $this->stretch_name;
    }
}