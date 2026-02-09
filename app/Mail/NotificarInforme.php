<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificarInforme extends Mailable
{
    use Queueable, SerializesModels;

    public $investigador;
	public $informe;
    public $fecha_inicio;
    public $fecha_fin;
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
        $this->fecha_inicio = $data['fecha_inicio'];		
        $this->fecha_fin = $data['fecha_fin'];		
        $this->signature = $data['signature'];		
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
		return $this->view('emails.notificarInforme');
    }
}
