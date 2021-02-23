<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FacturaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $file = $this->data['factura']->ser_fac.'.pdf';
        $asunto = "Factura ".$this->data['factura']->ser_fac;

        return $this->markdown('emails.factura')
                    ->with('data', $this->data)
                    ->subject($asunto)
                    ->from($this->data['from'],$this->data['nom_ape'])
                    ->attach(storage_path('facturas/'.$file), [
                        'as' => $file,
                        'mime' => 'application/pdf'
                ]);

    }
}

