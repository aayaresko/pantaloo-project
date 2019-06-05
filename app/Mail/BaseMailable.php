<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BaseMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($view, $viewData)
    {
        $this->view($view, $viewData);
    }

    public function to($data, $name = null)
    {
        $email = is_string($data) ? $data : $data->email;
        return parent::to($email, is_null($name) ? $name : $email);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this;
    }
}
