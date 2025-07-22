<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Paiement confirmé</title>
</head>
<body>
    <h2>Paiement confirmé</h2>

    <p>Bonjour {{ $inscription->name }},</p>

    <p>Nous avons bien reçu votre paiement pour la formation <strong>{{ $inscription->formation->titre }}</strong>.</p>

    <p><strong>Montant payé :</strong> {{ $inscription->montant }} €</p>
    <p><strong>Date :</strong> {{ $inscription->payment_date->format('d/m/Y H:i') }}</p>
    <p><strong>Référence :</strong> {{ $inscription->stripe_session_id }}</p>

    <p>Merci de votre confiance,</p>
    <p>L'équipe Miko Formation</p>
</body>
</html>
