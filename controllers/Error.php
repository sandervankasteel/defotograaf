<?php
/**
 * Remco Schipper
 * Date: 13/11/14
 * Time: 15:33
 */

namespace controllers;


class Error extends Controller {
    public function notFound($parameters) {
        $this->view->setPageTitle('404');

        $this->view->set('viewMethod', $parameters['method']);
        $this->view->set('viewController', $parameters['controller']);
    }

    public function accessDenied($parameters) {
        $this->view->setPageTitle('-');

        $this->view->set('viewMethod', $parameters['method']);
        $this->view->set('viewController', $parameters['controller']);    }
} 