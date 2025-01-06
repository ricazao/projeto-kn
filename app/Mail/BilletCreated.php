<?php

namespace App\Mail;

use App\Models\Debt\Debt;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BilletCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Debt $debt
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Novo boleto disponível',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.billet-created',
        );
    }
}
