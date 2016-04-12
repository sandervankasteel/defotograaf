<?php
/**
 * Remco Schipper
 * Date: 17/11/14
 * Time: 11:48
 */

namespace controllers;


use services\Authentication;
use services\Text;
use services\View;
use services\Router;

abstract class Controller{
    /** @var \services\View */
    protected $view = null;
    /** @var int */
    protected  $status = 200;

    /**
     * Called when a controller is constructed (with "new <controlername>()")
     */
    public function __construct() {
        $this->view = View::getInstance();
        $route = Router::getInstance()->called;

        if (!array_key_exists('page_title', $route['config'])) {
            $route['config']['page_title'] = $route['controller'];
        }

        if (!array_key_exists('nav_title', $route['config'])) {
            $route['config']['nav_title'] = $route['controller'];
        }

        $this->view->setPageTitle($route['config']['page_title']);
        $this->view->setNavigationBarTitle($route['config']['nav_title']);
    }

    /**
     * Called at the end of each request, renders the view etc.
     */
    public function _finish() {
        $route = Router::getInstance()->called;

        if ($this->view->getView() === null) {
            $this->setView(array($route['controller'], $route['method']));
        }

        $path = '/' . $route['controller'] . '/' . $route['method'];

        //http_response_code($this->status);

        $this->view->set('view_user', Authentication::getInstance()->get());
        $this->view->set('view_page_url', strtolower($path));
        $this->view->set('view_text', Text::getInstance());
        $this->view->render();
    }

    /**
     * Redirects the user to another page
     * @param string $controller
     * @param string $action
     * @param array $params
     */
    protected function redirect($controller, $action, array $params = array()) {
        $data = array_merge(array($controller, $action), $params);

        if (count($data) === 2) {
            if ($data[1] === 'index') {
                unset($data[1]);
            }
            if ($data[0] === 'index') {
                unset($data[0]);
            }
        }

        header('Location: /' . implode('/', $data));
    }

    /**
     * Set the view to another file
     * @param string|array $view
     */
    protected function setView($view) {
        if (is_array($view)) {
            $view = implode(DIRECTORY_SEPARATOR, $view);
        }

        $this->view->setView(strtolower($view));
    }

    /**
     * @param int $status
     */
    protected function setStatus($status) {
        $this->status = $status;
    }

    /**
     * @param string $key
     * @param array $params
     * @return null|string
     */
    protected function text($key, array $params = array()) {
        return Text::get($key);
    }
} 