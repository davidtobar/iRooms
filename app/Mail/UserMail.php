<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $subject_f;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $subject_f)
    {
        $this->data = $data;
        $this->subject_f = $subject_f;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject_f)
            ->view('core.pages.mail.user_manager')->with(['data', $this->data]);
    }
}
