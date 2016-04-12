<?php
/**
 * Created by PhpStorm.
 * User: Sander
 * Date: 11/14/14
 * Time: 2:42 PM
 */

namespace services;

use exceptions\route\notFound;

class View extends Service
{
    private $mainFile = null;
    private $viewMap = null;
    private $values = array();

    public function __construct() {
        $this->viewMap = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'views';
    }

    public function set($key, $value) {
        $this->values[$key] = $value;
    }

    public function setView($fileName) {
        $this->mainFile = $this->viewMap . DIRECTORY_SEPARATOR . $fileName .".php";

        if (!file_exists($this->mainFile)) {
            throw new notFound;
        }
    }

    public function getView() {
        return $this->mainFile;
    }

    public function setNavigationBarTitle($title) {
        $this->values['view_navigation_bar_title'] = $title;
    }

    public function setPageTitle($title) {
        $this->values['view_page_title'] = $title;
    }

    public function setActiveMenuItem($item) {
        $this->values['view_menu_item'] = $item;
    }

    public function render() {
        extract($this->values);

        ob_start();

        include($this->viewMap . DIRECTORY_SEPARATOR . 'layout' . DIRECTORY_SEPARATOR . 'header.php');
        include($this->mainFile);
        include($this->viewMap . DIRECTORY_SEPARATOR . 'layout' . DIRECTORY_SEPARATOR . 'footer.php');

        echo ob_get_clean();
    }
}