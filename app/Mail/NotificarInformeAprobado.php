<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificarInformeAprobado extends Mailable
{
    use Queueable, SerializesModels;

    public $investigador;
	public $informe;
	public $signature;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
		
		
		
        $this->investigador = $data['investigador'];
        $this->informe = $data['informe'];
	    $this->signature = $data['signature'];		
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
		return $this->view('emails.notificarInformeAprobado');
    }
}
