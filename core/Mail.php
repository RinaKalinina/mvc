<?php

namespace Core;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class Mail
{
    private $mailer;
    private $message;

    public function __construct()
    {
        $this->is_valid([
            'SMTP_HOST' => SMTP_HOST,
            'SMTP_PORT' => SMTP_PORT,
            'SMTP_USER_NAME' => SMTP_USER_NAME,
            'SMTP_PASSWORD' => SMTP_PASSWORD,
            'SMTP_ENCRYPTION' => SMTP_ENCRYPTION
        ]);

        $transport = (new Swift_SmtpTransport(SMTP_HOST, SMTP_PORT))
            ->setUsername(SMTP_USER_NAME)
            ->setPassword(SMTP_PASSWORD)
            ->setEncryption(SMTP_ENCRYPTION);

        $this->mailer = new Swift_Mailer($transport);
    }

    public function createAndSend(string $body, $title = 'feedback', $to = [SMTP_TO])
    {
        $this->is_valid([
            'SMTP_FROM_ADDRESS' => SMTP_FROM_ADDRESS,
            'SMTP_FROM_NAME' => SMTP_FROM_NAME,
            'SMTP_TO' => SMTP_TO
        ]);

        $this->message = (new Swift_Message($title))
            ->setFrom([SMTP_FROM_ADDRESS => SMTP_FROM_NAME])
            ->setTo($to)
            ->setBody($body);

        $this->mailer->send($this->message);
    }

    public function is_valid(array $data)
    {
        foreach ($data as $key => $value) {
            if (empty($value)) {
                throw new \InvalidArgumentException("Not installed $key");
            }
        }
    }

}