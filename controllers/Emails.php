<?php
/**
 * Remco Schipper
 * Date: 04/12/14
 * Time: 20:54
 */

namespace controllers;


use entities\EmailTemplate;
use config\Mail as config;
use entities\OrderStatus;
use exceptions\database\Prepare;
use services\Validator;

class Emails extends Controller {
    private $validationFields = array('subject' => array('empty' => false, 'length' => array(1, 255), 'message' => 'emails.validation.subject'),
        'reply_to' => array('empty' => false, 'length' => array(1, 254), 'message' => 'emails.validation.reply_to'),
        'reply_to_name' => array('empty' => false, 'length' => array(1, 64), 'message' => 'emails.validation.reply_to_name'),
        'option' => array('empty' => false, 'length' => array(1, 254), 'message' => 'emails.validation.option'),
        'body' => array('empty' => true, 'length' => array(1, 9000), 'message' => 'emails.validation.body'),
        'body_html' => array('empty' => false, 'length' => array(1, 9000), 'message' => 'emails.validation.body_html'));

    private $accountOptions = array(0 => 'Registreren', 1 => 'Wachtwoord vergeten');

    private function getSpecialParams($action) {
        $special = config::$PARAMS['special'];

        if (isset($special[$action])) {
            $special = $special[$action];
        }
        else {
            $special = array();
        }
        unset(config::$PARAMS['special']);

        return array_merge(config::$PARAMS, $special);
    }

    public function index() {
        $this->redirect('emails', 'overzicht', array('statuses'));
    }

    public function show($parameters) {
        $like = null;
        $view = $parameters['id'];

        if ($view === 'statuses') {
            $like = 'status';
        }
        else {
            $like = 'account';
        }

        $query = sprintf('SELECT * FROM %s WHERE action LIKE "%s_%s"', EmailTemplate::$table, $like, '%');

        $this->view->set('showTemplates', EmailTemplate::getByQuery($query));
        $this->view->set('showView', $like);
    }

    public function create($parameters) {
        $action = $parameters['action'];

        $options = null;

        if ($action === 'status') {
            $options = OrderStatus::getAll();
        }
        else {
            $options = $this->accountOptions;
        }


        if (isset($_POST['submit'])) {
            $validator = Validator::getInstance();
            $data = $validator->parsePost($_POST, $this->validationFields);
            $this->view->set('createValues', $data['values']);

            if ($data['result'] === true) {
                $template = new EmailTemplate($data['values']);
                $template->setAction($action . '_' . $data['values']['option']);

                try {
                    $template->save();
                    $this->view->set('createResult', array('id' => $template->getId(), 'subject' => $template->getSubject()));
                }
                catch(Prepare $e) {
                    if ($e->getCode() === 1062) {
                        $this->view->set('createError', array('option' => $this->text('emails.validation.double')));
                    }
                    else {
                        $this->view->set('createResult', false);
                    }
                }
            }
            else {
                $this->view->set('createError', $data['result']);
            }
        }

        $this->view->set('createOptions', $options);
        $this->view->set('createAction', $action);
        $this->view->set('createParams', $this->getSpecialParams($action));
    }

    public function edit($parameter) {
        $template = EmailTemplate::getById($parameter['id']);

        if ($template === null) {
            $this->redirect('emails', 'index');
        }
        else {
            $explodeAction = explode('_', $template->getAction());
            $action = $explodeAction[0];
            $value = $explodeAction[1];

            if (isset($_POST['submit'])) {
                $validator = Validator::getInstance();
                $data = $validator->parsePost($_POST, $this->validationFields);

                if ($data['result'] === true) {
                    $values = $data['values'];

                    $template->setSubject($values['subject']);
                    $template->setBody($values['body']);
                    $template->setBodyHtml($values['body_html']);
                    $template->setReplyTo($values['reply_to']);
                    $template->setReplyToName($values['reply_to_name']);

                    if ($action === 'status') {
                        $template->setAction('status_' . $values['option']);
                    }

                    $value = $values['option'];

                    try {
                        $template->update();
                        $this->view->set('editResult', array('id' => $template->getId(), 'subject' => $template->getSubject()));
                    }
                    catch(Prepare $e) {
                        if ($e->getCode() === 1062) {
                            $this->view->set('editError', array('option' => $this->text('emails.validation.double')));
                        }
                        else {
                            $this->view->set('editResult', false);
                        }
                    }
                }
                else {
                    $this->view->set('editError', $data['result']);
                }
            }

            $options = null;
            if ($action === 'status') {
                $options = OrderStatus::getAll();
            }
            else {
                $options = $this->accountOptions;
            }

            $this->view->set('editTemplate', $template);
            $this->view->set('editAction', $action);
            $this->view->set('editValue', $value);
            $this->view->set('editOptions', $options);
            $this->view->set('editParams', $this->getSpecialParams($action));
        }
    }

    public function remove($parameters) {
        $template = EmailTemplate::getById($parameters['id']);

        if ($template === null) {
            $this->redirect('emails', 'index');
        }
        else {
            try {
                $template->delete();
                $this->redirect('emails', 'index');
            }
            catch(Prepare $e) {
                $this->view->set('removeResult', false);
            }
        }
    }
} 