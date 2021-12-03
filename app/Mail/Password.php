<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Password extends Mailable
{
    use Queueable, SerializesModels;
    public $text,$email;

    /**
     * Create a new message instance.
     * @return void
     * @var $params
     * @var $email
     */
    public function __construct($text,$email = '')
    {
        $this->text = $text;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->email)
        return $this->to($this->email)
            ->subject(__('auth/forget.forget_title'))
            ->view('emails.password');
    }
}
