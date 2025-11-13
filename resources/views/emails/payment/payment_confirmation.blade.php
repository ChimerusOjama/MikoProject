<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Paiement Confirmé - Miko Formation</title>
  <style>
    :root {
      --primary: #3F51B5;
      --primary-dark: #303F9F;
      --secondary: #4CAF50;
      --secondary-dark: #388E3C;
      --light: #F5F5F5;
      --dark: #212121;
      --gray: #757575;
      --white: #FFFFFF;
      --success: #4CAF50;
      --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--light);
      color: var(--dark);
      margin: 0;
      padding: 0;
      line-height: 1.6;
    }
    
    .email-container {
      max-width: 600px;
      margin: 30px auto;
      background: var(--white);
      border-radius: 10px;
      overflow: hidden;
      box-shadow: var(--card-shadow);
    }
    
    .email-header {
      background: linear-gradient(135deg, var(--success) 0%, var(--secondary-dark) 100%);
      padding: 25px 30px;
      text-align: center;
      color: var(--white);
    }
    
    .logo {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: 28px;
      margin-bottom: 10px;
    }
    
    .logo span {
      color: var(--white);
      opacity: 0.9;
    }
    
    .success-icon {
      font-size: 48px;
      margin-bottom: 15px;
    }
    
    .email-body {
      padding: 30px;
    }
    
    .email-title {
      color: var(--success);
      font-family: 'Poppins', sans-serif;
      font-weight: 600;
      margin-top: 0;
      position: relative;
      padding-bottom: 15px;
      text-align: center;
    }
    
    .email-title:after {
      content: '';
      display: block;
      width: 60px;
      height: 4px;
      background: var(--success);
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
    }
    
    .highlight {
      color: var(--primary-dark);
      font-weight: 600;
    }
    
    .payment-details {
      background: var(--light);
      border-left: 4px solid var(--success);
      padding: 20px;
      margin: 25px 0;
      border-radius: 0 8px 8px 0;
    }
    
    .detail-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
      padding-bottom: 10px;
      border-bottom: 1px solid #e0e0e0;
    }
    
    .detail-row:last-child {
      border-bottom: none;
      margin-bottom: 0;
    }
    
    .detail-label {
      font-weight: 600;
      color: var(--gray);
    }
    
    .detail-value {
      font-weight: 600;
      color: var(--dark);
    }
    
    .amount {
      color: var(--success);
      font-size: 1.2em;
    }
    
    .next-steps {
      background: #E8F5E8;
      border: 1px solid var(--success);
      border-radius: 8px;
      padding: 20px;
      margin: 25px 0;
    }
    
    .steps-title {
      color: var(--success);
      font-weight: 600;
      margin-top: 0;
    }
    
    .step {
      display: flex;
      margin-bottom: 15px;
    }
    
    .step-number {
      background: var(--success);
      color: var(--white);
      width: 25px;
      height: 25px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
      flex-shrink: 0;
      font-size: 0.9em;
      font-weight: 600;
    }
    
    .cta-button {
      display: inline-block;
      background: var(--success);
      color: var(--white) !important;
      padding: 12px 30px;
      border-radius: 30px;
      text-decoration: none;
      font-weight: 600;
      margin: 20px 0;
      transition: all 0.3s ease;
      box-shadow: 0 4px 6px rgba(76, 175, 80, 0.2);
      text-align: center;
    }
    
    .cta-button:hover {
      background: var(--secondary-dark);
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba(76, 175, 80, 0.3);
    }
    
    .email-footer {
      background: var(--primary-dark);
      color: var(--white);
      text-align: center;
      padding: 20px;
      font-size: 0.9rem;
    }
    
    .footer-links {
      margin-top: 15px;
    }
    
    .footer-links a {
      color: var(--secondary);
      text-decoration: none;
      margin: 0 10px;
      transition: all 0.3s ease;
    }
    
    .footer-links a:hover {
      color: var(--secondary-dark);
    }
    
    .support-note {
      text-align: center;
      color: var(--gray);
      font-size: 0.9em;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="email-container">
    <!-- En-tête -->
    <header class="email-header">
      <div class="logo">MIKO<span>FORMATION</span></div>
      <div class="success-icon">✅</div>
      <h1>Paiement Confirmé !</h1>
    </header>
    
    <!-- Corps -->
    <main class="email-body">
      <h2 class="email-title">Félicitations {{ $inscription->name }} ! 🎉</h2>
      
      <p>Votre paiement pour la formation a été traité avec succès. Votre inscription est maintenant confirmée.</p>
      
      <div class="payment-details">
        <div class="detail-row">
          <span class="detail-label">Formation :</span>
          <span class="detail-value">{{ $inscription->choixForm }}</span>
        </div>
        
        <div class="detail-row">
          <span class="detail-label">Montant payé :</span>
          <span class="detail-value amount">{{ number_format($inscription->montant, 0, ',', ' ') }} FCFA</span>
        </div>
        
        <div class="detail-row">
          <span class="detail-label">Date du paiement :</span>
          <span class="detail-value">{{ $inscription->payment_date ? $inscription->payment_date->format('d/m/Y à H:i') : now()->format('d/m/Y à H:i') }}</span>
        </div>
        
        <div class="detail-row">
          <span class="detail-label">Statut :</span>
          <span class="detail-value" style="color: var(--success);">✅ Confirmé</span>
        </div>
      </div>
      
      <div class="next-steps">
        <h3 class="steps-title">Prochaines étapes :</h3>
        
        <div class="step">
          <div class="step-number">1</div>
          <div>
            <strong>Accès à la plateforme</strong><br>
            Vous recevrez sous peu vos identifiants pour accéder à notre plateforme d'apprentissage.
          </div>
        </div>
        
        <div class="step">
          <div class="step-number">2</div>
          <div>
            <strong>Début de la formation</strong><br>
            Consultez votre espace personnel pour connaître les dates de début et le planning.
          </div>
        </div>
        
        <div class="step">
          <div class="step-number">3</div>
          <div>
            <strong>Support technique</strong><br>
            Notre équipe reste à votre disposition pour toute question technique ou pédagogique.
          </div>
        </div>
      </div>
      
      <div style="text-align: center;">
        <a href="{{ route('uFormation') }}" class="cta-button">
          📚 Accéder à mes formations
        </a>
      </div>
      
      <p class="support-note">
        Besoin d'aide ? Contactez notre support à 
        <a href="mailto:support@mikoformation.com" style="color: var(--success);">support@mikoformation.com</a>
      </p>
      
      <p>Merci de votre confiance et à très bientôt,</p>
      <p><strong>L'équipe Miko Formation</strong></p>
    </main>
    
    <!-- Pied de page -->
    <footer class="email-footer">
      <p>Miko Formation – Connaissance, Compétence, Excellence</p>
      <div class="footer-links">
        <a href="{{ url('/') }}">Accueil</a> | 
        <a href="{{ route('listing') }}">Formations</a> | 
        <a href="{{ route('contact') }}">Contact</a>
      </div>
      <p style="margin-top: 15px; font-size: 0.8em; opacity: 0.8;">
        Cet email a été envoyé automatiquement, merci de ne pas y répondre.
      </p>
    </footer>
  </div>
</body>
</html>