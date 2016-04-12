<?php
/**
 * Remco Schipper
 * Date: 04/12/14
 * Time: 19:28
 */

namespace entities;


class EmailTemplate extends Entity {
    protected $email_id = null;
    protected $action = null;
    protected $subject = null;
    protected $body = null;
    protected $body_html = null;
    protected $reply_to = null;
    protected $reply_to_name = null;

    public static $table = 'email_templates';
    public static $id_field = 'email_id';

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction($action) {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getBodyHtml()
    {
        return $this->body_html;
    }

    /**
     * @return string
     */
    public function getReplyTo()
    {
        return $this->reply_to;
    }

    /**
     * @return string
     */
    public function getReplyToName()
    {
        return $this->reply_to_name;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param null $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @param string $body_html
     */
    public function setBodyHtml($body_html)
    {
        $this->body_html = $body_html;
    }

    /**
     * @param string $reply_to
     */
    public function setReplyTo($reply_to)
    {
        $this->reply_to = $reply_to;
    }

    /**
     * @param string $reply_to_name
     */
    public function setReplyToName($reply_to_name)
    {
        $this->reply_to_name = $reply_to_name;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }


}
?>