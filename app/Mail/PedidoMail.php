<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;

use Illuminate\Mail\Mailable;

use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class PedidoMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $pedido;
    public $archivo;

    public function __construct($pedido, $archivo = null)
    {
        $this->pedido = $pedido;
        $this->archivo = $archivo; // Archivo adjunto opcional
    }


    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->subject('Nueva pedido realizado')
            ->view('emails.pedido_form') // Vista del correo
            ->with(
                [
                    'pedido' => $this->pedido,
                ]
            );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        if ($this->archivo) {
            $attachments[] = Attachment::fromPath($this->archivo->getRealPath())
                ->as($this->archivo->getClientOriginalName())
                ->withMime($this->archivo->getMimeType());
        }

        return $attachments;
    }
}
