<?php

namespace App;

use App\exceptions\CouldNotSendEmailException;

class Mailer
{
    private $mailer;

    public function __construct(\SimpleMail $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send($email, $subject, $message)
    {

        $send = $this->mailer->setTo($email, 'Dear user')
            ->setFrom('info@info.ru', 'Educational project')
            ->setSubject($subject)
            ->setMessage($message)
            ->setHtml()
            ->setWrap(100)
            ->send();

        if (!$send) {
            throw new CouldNotSendEmailException();
        }
    }

}