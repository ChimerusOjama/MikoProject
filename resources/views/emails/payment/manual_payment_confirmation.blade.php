<!DOCTYPE html>
<html>
<head>
    <title>Confirmation de paiement</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #2962ff; color: white; padding: 10px; text-align: center; }
        .content { padding: 20px; border: 1px solid #ddd; }
        .footer { text-align: center; padding: 10px; font-size: 0.8em; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Miko Formation</h2>
        </div>
        
        <div class="content">
            <h3>Confirmation de paiement</h3>
            <p>Bonjour,</p>
            
            <p>Nous vous confirmons la réception de votre paiement pour la formation <strong>{{ $paiement->inscription->choixForm }}</strong>.</p>
            
            <h4>Détails du paiement :</h4>
            <ul>
                <li><strong>Montant :</strong> {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</li>
                <li><strong>Date :</strong> {{ $paiement->date_paiement->format('d/m/Y') }}</li>
                <li><strong>Méthode :</strong> {{ $paiement->mode }}</li>
                @if($paiement->reference)
                <li><strong>Référence :</strong> {{ $paiement->reference }}</li>
                @endif
            </ul>
            
            <p>Vous pouvez suivre l'avancement de votre inscription depuis votre espace personnel.</p>
            
            <p>Merci pour votre confiance !</p>
        </div>
        
        <div class="footer">
            <p>Miko Formation &copy; {{ date('Y') }}</p>
            <p>Ceci est un message automatique, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>