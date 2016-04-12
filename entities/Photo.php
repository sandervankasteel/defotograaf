<?php
/**
 * Created by PhpStorm.
 * User: Joey
 * Date: 1-12-2014
 * Time: 13:39
 */

namespace entities;

class Photo extends Entity
{
    protected $photo_id = null;
    protected $filename = null;
    protected $upload_date = null;
    protected $effect = null;
    protected $hash = null;
    protected $directory = null;
    protected $stretch = null;
    protected $user_id = null;

    public static $table = 'photos';

    private $_effect = null;
    private $_stretch = null;
    private $_user = null;

    /**
     * @return null|\entities\PhotoEffect
     */

    public function getPhotoEffect()
    {
        if ($this->_effect === null) {
            $this->_effect = PhotoEffect::getBy('picture_effect_id', $this->effect, TRUE);
        }
        return $this->_effect;
    }

    /**
     * @return null|\entities\PhotoFillFit
     */

    public function getPhotoFillFit()
    {
        if ($this->_stretch === null) {
            $this->_stretch = PhotoFillFit::getBy('photo_stretch_id', $this->stretch, TRUE);
        }
        return $this->_stretch;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @return int
     */
    public function getPhotoId()
    {
        return $this->photo_id;
    }

    /**
     * @return datetime
     */
    public function getUploadDate()
    {
        return $this->upload_date;
    }

    /**
     * @return int
     */
    public function getEffect()
    {
        return $this->effect;
    }

    /**
     * @return int
     */
    public function getStretch()
    {
        return $this->stretch;
    }

    public function getHash() {
        return $this->hash;
    }

    public function setEffect($id) {
        $this->effect = $id;
    }

    public function setStretch($id) {
        $this->stretch = $id;
    }

    /**
     * @return \entities\User
     */
    public function getUser() {
        if ($this->_user === null) {
            $this->_user = User::getById($this->user_id);
        }

        return $this->_user;
    }



}