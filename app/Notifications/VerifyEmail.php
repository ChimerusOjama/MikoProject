<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmail extends BaseVerifyEmail
{
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);
        $displayableActionUrl = str_replace(['http://', 'https://'], '', $verificationUrl);

        return (new MailMessage)
            ->subject('Vérifiez votre adresse e-mail')
            ->view('emails.verify-email', [
                'user' => $notifiable,
                'actionUrl' => $verificationUrl,
                'displayableActionUrl' => $displayableActionUrl,
                'full_name' => $notifiable->full_name,
                'salutation' => 'Cordialement, ' . config('app.name')
            ]);
    }
}