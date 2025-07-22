<?php

namespace App\Mail;

use App\Models\Inscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationAnnulee extends Mailable
{
    use Queueable, SerializesModels;

    public $inscription;

    public function __construct(Inscription $inscription)
    {
        $this->inscription = $inscription;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation d\'annulation de votre réservation',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.inscription.annulation',
            with: [
                'inscription' => $this->inscription,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
