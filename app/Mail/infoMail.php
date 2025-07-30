<?php

namespace App\Mail;

use App\Models\Formation;
use App\Models\Inscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use App\Http\Controllers\FirstController;

class infoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $formation;
    public $inscription;
    public $lienPaiement;

    /**
     * Create a new message instance.
     */
    public function __construct($user, Formation $formation, Inscription $inscription)
    {
        $this->user = $user;
        $this->formation = $formation;
        $this->inscription = $inscription;
        $this->lienPaiement = app(FirstController::class)->generateStripeLink($inscription->id);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('formation@miko.com', 'Miko Formation'),
            subject: 'Votre pré-inscription a bien été reçue',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.inscription.info',
            with: [
                'user' => $this->user,
                'formation' => $this->formation,
                'inscription' => $this->inscription,
                'lienPaiement' => $this->lienPaiement, // 👈 important
            ],
        );
    }



    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
