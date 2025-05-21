<?php

namespace App\Mail;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PedidoStatusAtualizado extends Mailable
{
    use Queueable, SerializesModels;

    public $pedido;
    public $novoStatus;

    /**
     * Create a new message instance.
     */
    public function __construct(Pedido $pedido, string $novoStatus)
    {
        $this->pedido = $pedido;
        $this->novoStatus = $novoStatus;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Atualização no Status do Seu Pedido!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pedido_status_atualizado',
            with: [
                'pedido' => $this->pedido,
                'novoStatus' => $this->novoStatus,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
