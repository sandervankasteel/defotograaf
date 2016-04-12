<?php
/**
 * Created by PhpStorm.
 * User: Joey
 * Date: 3-12-2014
 * Time: 19:33
 */

namespace entities;

class PhotoEffect extends Entity
{
    protected $picture_effect_id = null;
    protected $effect_name = null;

    public static $table = 'photo_effect';

    /**
     * @return string
     */
    public function getName()
    {
        return $this->effect_name;
    }
}