<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class  ContactUs extends Mailable
{
    use Queueable, SerializesModels;

    protected $store;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($store)
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('view.name');
    }
}
