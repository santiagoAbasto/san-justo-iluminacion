<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;

use Illuminate\Mail\Mailable;

use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class TrabajaConNosotrosMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $data;
    public $archivo;

    public function __construct($data, $archivo = null)
    {
        $this->data = $data;
        $this->archivo = $archivo; // Archivo adjunto opcional
    }


    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->subject('Nuevo contacto desde la pagina web')
            ->view('emails.trabaja_con_nosotros') // Vista del correo
            ->with(
                [
                    'data' => $this->data,
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
