<?php
/**
 * Remco Schipper / Tim Oosterbroek
 * Date: 13/11/14
 * Time: 13:53
 */

namespace services;

use config\Routes;
use controllers\Error;
use exceptions\route\accessDenied;
use exceptions\route\notFound;

class Router extends Service {
    private $route = 'index';
    private $method = 'index';
    public $called = null;

    /**
     * Main function, starts the routing process
     */
    public function work() {
        if(isset($_GET['r'])) {
            $this->route = $_GET['r'];
        }

        $this->transform();
    }

    /**
     * Transforms the url to an understandable format
     * @throws notFound
     */
    private function transform() {
        $parts = explode('/', $this->route);

        if (end($parts) === '') {
            array_pop($parts);
        }

        $controller = ucfirst($parts[0]);
        $method = (isset($parts[1])) ? $parts[1] : $this->method;

        unset($parts[0]);
        unset($parts[1]);

        $this->getConfig(array('controller' => $controller, 'method' => $method, 'parameters' => $parts));
    }

    /**
     * Fetches the configuration from the config file
     * @param array $route
     */
    private function getConfig(array $route) {
        try {
            $data = Routes::get($route);

            if (isset($data['_alias_controller']) && isset($data['_alias_method'])) {
                $route['controller'] = $data['_alias_controller'];
                $route['method'] = $data['_alias_method'];

                unset($data['_alias_controller']);
                unset($data['_alias_method']);
            }

            $route['config'] = $data;
        }
        catch(notFound $e) {
            $route['config'] = array('parameters' => array('controller', 'method'));
            $route['parameters'] = array($route['controller'], $route['method']);
            $route['controller'] = 'Error';
            $route['method'] = 'notFound';
        }

        $this->assignVars($route);
    }

    /**
     * Matches the parameter names with the values from the URL
     * @param array $route
     */
    private function assignVars(array $route) {
        if (array_key_exists('parameters', $route['config'])) {
            $parameters = $route['config']['parameters'];

            if (count($parameters) === count($route['parameters'])) {
                $route['parameters'] = array_combine($parameters, $route['parameters']);;
            }
            else {
                $route['parameters'] = array('controller' => $route['controller'], 'method' => $route['method']);
                $route['controller'] = 'Error';
                $route['method'] = 'notFound';
            }
        }

        $this->checkAccess($route);
    }

    /**
     * Checks if the user is allowed to open the URL
     * @param array $route
     */
    private function checkAccess(array $route) {
        $auth = Authentication::getInstance();

        if (!$auth->check($route)) {
            $route['parameters'] = array('controller' => $route['controller'], 'method' => $route['method']);
            $route['controller'] = 'Error';
            $route['method'] = 'accessDenied';
        }

        $this->_finish($route);
    }

    /**
     * Finishes the routing process by invoking the controller
     * @param $route
     */
    private function _finish(array $route) {
        $this->called = $route;

        $class = '\\controllers\\' . $route['controller'];
        $method = $route['method'];

        /** @var \controllers\Controller $target */
        $target = new $class();
        $target->$method($route['parameters']);
        $target->_finish();
    }
}