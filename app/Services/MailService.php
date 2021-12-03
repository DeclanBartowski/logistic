<?php


namespace App\Services;

use App\Mail\Feedback;
use Illuminate\Support\Facades\Mail;

class MailService
{

    public static function send($emailTo, $subject, $text)
    {
        return Mail::send(new Feedback([
            'email' => $emailTo,
            'subject' => $subject,
            'text' => $text
        ]));
    }

}
