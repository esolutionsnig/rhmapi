<?php

namespace App\Mail;

use App\Model\Contactus;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactUsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contactus;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Contactus $contactus)
    {
        $this->contactus = $contactus;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.contactus.mailsent');
    }
}
