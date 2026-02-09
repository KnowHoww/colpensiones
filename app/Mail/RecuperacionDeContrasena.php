<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecuperacionDeContrasena extends Mailable
{
    use Queueable, SerializesModels;

    public $password;
    public $name;
    public $signature;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->password = $data['password'];
        $this->name = $data['name'];
        $this->signature = $data['signature'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template = rand ( 1 , 3 );
        if ($template ==2)
            return $this->view('emails.olvidoContrasena2');
        if ($template ==3)
            return $this->view('emails.olvidoContrasena3');
        return $this->view('emails.olvidoContrasena');

    }
}
