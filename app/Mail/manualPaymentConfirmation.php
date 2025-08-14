<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Paiement; // Ajout de l'import
use Carbon\Carbon; // Import pour la gestion des dates

class manualPaymentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $paiement; // Variable publique pour la vue

    /**
     * Create a new message instance.
     */
    public function __construct(Paiement $paiement) // Injection du modèle
    {
        $this->paiement = $paiement;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de votre paiement', // Sujet en français
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Formatage de la date pour la vue
        $dateFormatted = Carbon::parse($this->paiement->date_paiement)->format('d/m/Y');
        
        return new Content(
            view: 'emails.payment.manual_payment_confirmation',
            with: [
                'paiement' => $this->paiement, // Passage de la variable
                'dateFormatted' => $dateFormatted // Date pré-formatée
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
        return [];
    }
}