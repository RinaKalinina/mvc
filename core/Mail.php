<?php

namespace Core;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class Mail
{
    private $mailer;
    private $message;

    public function getConnection()
    {
        $transport = (new Swift_SmtpTransport(SMTP_HOST, SMTP_PORT))
            ->setUsername(SMTP_USER_NAME)
            ->setPassword(SMTP_PASSWORD)
            ->setEncryption(SMTP_ENCRYPTION)
        ;

        $this->mailer = new Swift_Mailer($transport);

        return $this;
    }

    public function create(string $body, $title = 'feedback', $to = [SMTP_TO])
    {
        $this->message = (new Swift_Message($title))
            ->setFrom([SMTP_FROM_ADDRESS => SMTP_FROM_NAME])
            ->setTo($to)
            ->setBody($body);

        return $this;
    }

    public function send()
    {
        return $this->mailer->send($this->message);
    }
}