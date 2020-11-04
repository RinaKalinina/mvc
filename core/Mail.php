<?php

namespace Core;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class Mail
{
    protected $to = SMTP_TO;

    public function __construct()
    {
        if (empty(SMTP_HOST) && !is_string(SMTP_HOST)) {
            throw new \InvalidArgumentException('Not installed SMTP_HOST in config.php');
        }

        if (empty(SMTP_PORT) && !is_int(SMTP_PORT)) {
            throw new \InvalidArgumentException('Not installed SMTP_PORT in config.php');
        }

        if (empty(SMTP_USER_NAME) && !is_string(SMTP_USER_NAME)) {
            throw new \InvalidArgumentException('Not installed SMTP_USER_NAME in config.php');
        }

        if (empty(SMTP_PASSWORD) && !is_string(SMTP_PASSWORD)) {
            throw new \InvalidArgumentException('Not installed SMTP_HOST in config.php');
        }

        if (empty(SMTP_ENCRYPTION) && !is_string(SMTP_ENCRYPTION)) {
            throw new \InvalidArgumentException('Not installed SMTP_ENCRYPTION in config.php');
        }

        if (empty(SMTP_FROM_ADDRESS) && !is_string(SMTP_FROM_ADDRESS)) {
            throw new \InvalidArgumentException('Not installed SMTP_FROM_ADDRESS in config.php');
        }

        if (empty(SMTP_FROM_NAME) && !is_string(SMTP_FROM_NAME)) {
            throw new \InvalidArgumentException('Not installed SMTP_FROM_NAME in config.php');
        }

        if (empty(SMTP_TO) && !is_string(SMTP_TO)) {
            throw new \InvalidArgumentException('Not installed SMTP_TO in config.php');
        }
    }

    protected function getTransport()
    {
        return (new Swift_SmtpTransport(SMTP_HOST, SMTP_PORT))
            ->setUsername(SMTP_USER_NAME)
            ->setPassword(SMTP_PASSWORD)
            ->setEncryption(SMTP_ENCRYPTION);
    }

    public function send(string $body, $title)
    {
        $to = !empty($this->to) ? $this->to : SMTP_TO;

        $message = (new Swift_Message($title))
            ->setFrom([SMTP_FROM_ADDRESS => SMTP_FROM_NAME])
            ->setTo($to)
            ->setBody($body);

        $mailer = new Swift_Mailer($this->getTransport());

        $mailer->send($message);
    }

    public function sendTo(string $body, $title, $to)
    {
        $this->to = $to;
        $this->send($body, $title);
    }

}