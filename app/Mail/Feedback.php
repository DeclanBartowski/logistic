<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Feedback extends Mailable
{
    use Queueable, SerializesModels;
    public $params;

    /**
     * Create a new message instance.
     * @return void
     * @var $params array
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->params['email'])
            ->subject($this->params['subject'])
            ->view('emails.feedback');
    }
}
