<?php
/**
 * Remco Schipper
 * Date: 04/12/14
 * Time: 18:29
 */

namespace services;


use entities\EmailTemplate;
use entities\User;
use exceptions\mail\CouldNotSendEmail;
use vendor\phpmailer\PHPMailer;
use config\Mail AS config;
use vendor\phpmailer\phpmailerException;

class Mail extends Service {
    /**
     * @return PHPMailer
     */
    private function PHPMailer() {
        $mailer = new PHPMailer();
        $mailer->isSMTP();

        if (config::$AUTH) {
            $mailer->SMTPAuth = true;
            $mailer->Password = config::$PASSWORD;
            $mailer->Username = config::$USERNAME;
        }

        if (config::$SSL !== null) {
            $mailer->SMTPSecure = config::$SSL;
        }

        $mailer->Host = config::$HOST;
        $mailer->Port = config::$PORT;

        $mailer->From = config::$FROM;
        $mailer->FromName = config::$NAME;

        return $mailer;
    }

    /**
     * @param string $text
     * @param array $vars
     * @return string mixed
     */
    private function replaceVars($text, $vars) {
        $config = config::$PARAM_TO_VALUE;

        preg_match_all("/%[^%]*%/", $text, $matches);

        for($i = 0; $i < count($matches[0]); $i++) {
            $match = substr($matches[0][$i], 1, -1);

            if (isset($config[$match])) {
                $conf = $config[$match];

                $method = $conf['method'];
                $value = $vars[$conf['entity']]->$method();

                $text = str_replace('%' . $match . '%', $value, $text);
            }
        }

        return $text;
    }

    /**
     * @param EmailTemplate $template
     * @param array $vars
     * @return array
     */
    private function parseVars(EmailTemplate $template, $vars) {
        $body_html = $this->replaceVars($template->getBodyHtml(), $vars);
        $body = $this->replaceVars($template->getBody(), $vars);

        return array('body_html' => $body_html, 'body' => $body);
    }

    /**
     * @param EmailTemplate $template
     * @param User $to
     * @param Array $content
     * @throws CouldNotSendEmail
     */
    private function sendMail(EmailTemplate $template, User $to, $content) {
        $mail = $this->PHPMailer();
        $mail->isHTML(true);

        $mail->addAddress($to->getEmail(), $to->getFirstName() . ' ' . $to->getLastName());

        if ($template->getReplyTo() !== null) {
            $mail->addReplyTo($template->getReplyTo(), $template->getReplyToName());
        }

        $mail->Subject = $template->getSubject();
        $mail->Body = $content['body_html'];
        $mail->AltBody = $content['body'];

        try {
            if ($mail->send()) {
                return true;
            } else {
                throw new CouldNotSendEmail($mail->ErrorInfo);
            }
        }
        catch (phpmailerException $e) {
            throw new CouldNotSendEmail($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param EmailTemplate $template
     * @param User $to
     * @param array $vars
     */
    public function send(EmailTemplate $template, User $to, $vars = array()) {
        if (!isset($vars['User'])) {
            $vars['User'] = $to;
        }
        if (!isset($vars['Address'])) {
            $vars['Address'] = $to->getAddress();
        }

        $content = $this->parseVars($template, $vars);

        return $this->sendMail($template, $to, $content);
    }
} 