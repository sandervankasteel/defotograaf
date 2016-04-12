<?php
/**
 * Remco Schipper / Sander van Kasteel
 * Date: 13/11/14
 * Time: 14:15
 */

namespace config;


use exceptions\route\notFound;
use services\View;

class Routes {
    private $routes = array(
        'Index' => array(
            'index' => array('access' => array('guest', 'user', 'admin'))
        ),
        'Customers' => array(
            'create' => array('access' => array('guest'), 'page_title' => 'Registreren'),
            'login' => array('access' => array('guest'), 'page_title' => 'Inloggen'),
            'logoff',
            'forgot' => array('access' => array('guest'), 'page_title' => 'Wachtwoord vergeten'),
            'reset' => array('access' => array('guest'), 'page_title' => 'Wachtwoord vergeten'),
            'zip',
            'show' => array('access' => array('admin'), 'page_title' => 'Klantenoverzicht'),
            'update' => array('access' => array('admin', 'user'), 'parameters' => array('id'), 'page_title' => 'Gegevens bewerken')

        ),
        'Photo' => array(
            'upload',
            'view' => array('parameters' => array('id', 'type')),
            'edit' => array('parameters' => array('id'))
        ),
        'Paymentmethods' => array(
            'index' => array('access' => array('admin')),
            'create' => array('access' => array('admin'), 'page_title' => 'Betaalmethode toevoegen'),
            'show' => array('access' => array('admin'), 'parameters' => array ('view'), 'page_title' => 'Betaalmethodes'),
            'edit' => array('access' => array('admin'), 'parameters' => array('id'), 'page_title' => 'Betaalmethode bewerken'),
            'remove' => array('access' => array('admin'), 'parameters' => array('id'), 'page_title' => 'Betaalmethode verwijderen')
        ),
        'Sendmethods' => array(
            'index' => array('access' => array('admin')),
            'create' => array('access' => array('admin'), 'page_title' => 'Verzend methode toevoegen'),
            'show' => array('access' => array('admin'), 'parameters' => array('view'), 'page_title' => 'Verzendmethodes'),
            'edit' => array('access' => array('admin'), 'parameters' => array('id'), 'page_title' => 'Verzendmethode bewerken'),
            'remove' => array('access' => array('admin'), 'parameters' => array('id'), 'page_title' => 'Verzendmethode verwijderen')
        ),
        'Coupons' => array(
            'index' => array('access' => array('admin')),
            'create' => array('access' => array('admin'), 'page_title' => 'Kortingscode toevoegen'),
            'show' => array('access' => array('admin'), 'parameters' => array('view'), 'page_title' => 'Kortingscodes'),
            'edit' => array('access' => array('admin'), 'parameters' => array('id'), 'page_title' => 'Kortingscode bewerken'),
            'remove' => array('access' => array('admin'), 'parameters' => array('id'), 'page_title' => 'Kortingscode verwijderen'),
        ),
        'Orders' => array(
            'index' => array('access' => array('admin'), 'page_title' => 'Bestellingsoverzicht'),
            'view' => array('access' => array('admin',  'user'), 'parameters' => array('id'), 'page_title' => 'Bestelling bekijken'),
            'create' => array('parameters' => array('step'), 'page_title' => 'Bestelling plaatsen')
        ),
        'Settings' => array(
            'index' => array('access' => array('admin'), 'page_title' => 'Instellingen'),
            'edit' => array('access' => array('admin'), 'page_title' => 'Instellingen wijzigen')
        ),
        'Statuses' => array(
            'index' => array('access' => array('admin')),
            'edit' => array('access' => array('admin'), 'parameters' => array ('id'), 'page_title' => 'Status bewerken'),
            'show' => array('access' => array('admin'), 'parameters' => array ('view'), 'page_title' => 'Statussen'),
            'create' => array('access' => array('admin'), 'page_title' => 'Status toevoegen'),
            'remove' => array('access' => array('admin'), 'parameters' => array ('id'), 'page_title' => 'Status verwijderen'),
        ),
        'Emails' => array(
            'index' => array('access' => array('admin')),
            'show' => array('access' => array('admin'), 'parameters' => array('id'), 'page_title' => 'E-mail sjablonen'),
            'create' => array('access' => array('admin'), 'parameters' => array('action'), 'page_title' => 'E-mail sjabloon toevoegen'),
            'edit' => array('access' => array('admin'), 'parameters' => array('id'), 'page_title' => 'E-mail sjabloon bewerken'),
            'remove' => array('access' => array('admin'), 'parameters' => array('id'), 'page_title' => 'E-mail sjabloon verwijderen')
        ),
        'PhotoEdit' => array(
            'index',
            'file_processing'
        ),
        'PhotoUpload' => array(
            'index',
            'file_upload',
        ),
        'Profile' => array(
            'index' => array('access' => array('admin', 'user'), 'page_title' => 'Profiel')
        ),
        'Articles' => array(
            'index' => array('access' => array('admin'), 'page_title' => 'Artikeloverzicht'),
            'create' => array('access' => array('admin'), 'page_title' => 'Artikel toevoegen'),
            'update' => array('access' => array('admin'), 'parameters' => array('id'), 'page_title' => 'Artikel bewerken')
        ),
        'File_uploads' => array(
            'index'
        )

    );

    private $aliasses = array(
        'Index' => array(
            'index' => array('Index', 'index')
        ),
        'Gebruikers' => array(
            'uitloggen' => array('Customers', 'logoff'),
            'inloggen' => array('Customers', 'login'),
            'registreren' => array('Customers', 'create'),
            'reset' => array('Customers', 'reset'),
            'vergeten' => array('Customers', 'forgot'),
            'bewerken' => array('Customers', 'update'),
            'overzicht' => array('Customers', 'show')
        ),
        'Betaalmethodes' => array(
            'index' => array('Paymentmethods', 'index'),
            'nieuw' => array('Paymentmethods', 'create'),
            'overzicht' => array('Paymentmethods', 'show'),
            'bewerken' => array('Paymentmethods', 'edit'),
            'verwijderen' => array('Paymentmethods', 'remove')
        ),
        'Verzendmethodes' => array(
            'index' => array('Sendmethods', 'index'),
            'nieuw' => array('Sendmethods', 'create'),
            'overzicht' => array('Sendmethods', 'show'),
            'bewerken' => array('Sendmethods', 'edit'),
            'verwijderen' => array('Sendmethods', 'remove')
        ),
        'Orderstatussen' => array(
            'index' => array('Statuses', 'index'),
            'overzicht' => array('Statuses', 'show'),
            'nieuw' => array('Statuses', 'create'),
            'bewerken' => array('Statuses', 'edit'),
            'verwijderen' => array('Statuses', 'remove'),
        ),
        'Emails' => array(
            'index' => array('Emails', 'index'),
            'overzicht' => array('Emails', 'show'),
            'nieuw' => array('Emails', 'create'),
            'bewerken' => array('Emails', 'edit'),
            'verwijderen' => array('Emails', 'remove')
        ),
        'Kortingscodes' => array(
            'index' => array('Coupons', 'index'),
            'nieuw' => array('Coupons', 'create'),
            'overzicht' => array('Coupons', 'show'),
            'bewerken' => array('Coupons', 'edit'),
            'verwijderen' => array('Coupons', 'remove')
        ),
        'Instellingen' => array(
            'index' => array('Settings', 'index'),
            'bewerken' => array('Settings', 'edit')
        ),
        'Bestelling' => array(
            'plaatsen' => array('Orders', 'create')
        ),
        'Foto' => array(
            'upload' => array('Photo', 'upload'),
            'bekijken' => array('Photo', 'view'),
            'bewerken' => array('Photo', 'edit')
        )
    );

    public static $instance = null;

    public function __construct() {
        $this::$instance = $this;
    }

    /**
     * Get the config for a method
     * @param string $method
     * @param array $controller
     * @return array|null
     */
    private function getMethod($method, $controller) {
        if (isset($controller[$method])) {
            return $controller[$method];
        }
        else if(in_array($method, $controller)) {
            return array();
        }
        else {
            return null;
        }
    }

    /**
     * Get the config for a controller
     * @param string $controller
     * @return array|null
     */
    private function getController($controller) {
        if (isset($this->routes[$controller])) {
            return $this->routes[$controller];
        }

        return null;
    }

    /**
     * !!!DO NOT USE THIS METHOD DIRECTLY!!!
     * @param array $route
     * @return array|null
     */
    public function _resolve(array $route, $warning = false) {
        $controller = $this->getController($route['controller']);

        if (is_array($controller)) {
            $method = $this->getMethod($route['method'], $controller);

            if ($method !== null) {
                if ($warning === true) {
                    $view = View::getInstance();
                    $view->set('router_warning', true);
                }

                return $method;
            }
        }

        return null;
    }

    /**
     * @param array $route
     * @return array|null
     */
    public function _resolveAlias(array $route) {
        if (isset($this->aliasses[$route['controller']])) {
            $controller = $this->aliasses[$route['controller']];

            if (isset($controller[$route['method']])) {
                $match = $controller[$route['method']];

                $route['controller'] = $match[0];
                $route['method'] = $match[1];

                $data = $this->_resolve($route);

                if ($data !== null) {
                    $data['_alias_controller'] = $match[0];
                    $data['_alias_method'] = $match[1];

                    return $data;
                }

                return null;
            }
        }
        else {
            return $this->_resolve($route, true);
        }
    }

    /**
     * Use this method to get the config for a route (should never be used!)
     * @param array $route
     * @return array|null
     * @throws notFound
     */
    public static function get(array $route) {
        if (isset($route['controller']) && isset($route['method'])) {
            $instance = self::getInstance();

            $route = $instance->_resolveAlias($route);

            if ($route !== null) {
                return $route;
            }
        }

        throw new notFound();
    }

    public static function getInstance() {
        return (self::$instance === null) ? new self() : self::$instance;
    }
}