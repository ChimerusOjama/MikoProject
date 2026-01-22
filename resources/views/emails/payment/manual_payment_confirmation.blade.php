<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confirmation de paiement - Miko Formation</title>
  <style>
    :root {
      --primary: #3F51B5;
      --primary-dark: #303F9F;
      --secondary: #FFCA28;
      --secondary-dark: #FFB300;
      --light: #F5F5F5;
      --dark: #212121;
      --gray: #757575;
      --white: #FFFFFF;
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
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
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
      color: var(--secondary);
    }
    
    .email-date {
      font-size: 0.9rem;
      opacity: 0.9;
    }
    
    .email-body {
      padding: 30px;
    }
    
    .email-title {
      color: var(--primary);
      font-family: 'Poppins', sans-serif;
      font-weight: 600;
      margin-top: 0;
      position: relative;
      padding-bottom: 15px;
    }
    
    .email-title:after {
      content: '';
      display: block;
      width: 60px;
      height: 4px;
      background: var(--secondary);
      position: absolute;
      bottom: 0;
      left: 0;
    }
    
    .highlight {
      color: var(--primary-dark);
      font-weight: 600;
    }
    
    .details-container {
      background: var(--light);
      border-left: 4px solid var(--secondary);
      padding: 20px;
      margin: 25px 0;
      border-radius: 0 8px 8px 0;
    }
    
    .detail-item {
      display: flex;
      margin-bottom: 12px;
      padding-bottom: 12px;
      border-bottom: 1px dashed #e0e0e0;
    }
    
    .detail-label {
      flex: 0 0 40%;
      font-weight: 600;
      color: var(--primary-dark);
    }
    
    .detail-value {
      flex: 1;
    }
    
    .cta-button {
      display: inline-block;
      background: var(--primary);
      color: var(--white) !important;
      padding: 12px 30px;
      border-radius: 30px;
      text-decoration: none;
      font-weight: 600;
      margin: 20px 0;
      transition: all 0.3s ease;
      box-shadow: 0 4px 6px rgba(63, 81, 181, 0.2);
    }
    
    .cta-button:hover {
      background: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba(63, 81, 181, 0.3);
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
    
    .confirmation-icon {
      text-align: center;
      font-size: 48px;
      color: #4CAF50;
      margin: 20px 0;
    }
  </style>
</head>
<body>
  <div class="email-container">
    <!-- En-tête -->
    <header class="email-header">
      <div class="logo">MIKO<span>FORMATION</span></div>
      <div class="email-date">{{ now()->format('d M Y') }}</div>
    </header>
    
    <!-- Corps -->
    <main class="email-body">
      <h2 class="email-title">Confirmation de paiement ✅</h2>
      
      <p>Bonjour {{ $paiement->inscription->name }},</p>
      
      <p>Votre paiement pour la formation <strong class="highlight">{{ $paiement->inscription->choixForm }}</strong> a été enregistré avec succès.</p>
      
      <div class="confirmation-icon">✓</div>
      
      <div class="details-container">
        <div class="detail-item">
          <div class="detail-label">Montant payé:</div>
          <div class="detail-value">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</div>
        </div>
        
        <div class="detail-item">
          <div class="detail-label">Date de paiement:</div>
          <div class="detail-value">{{ $paiement->date_paiement->format('d/m/Y') }}</div>
        </div>
        
        <div class="detail-item">
          <div class="detail-label">Méthode de paiement:</div>
          <div class="detail-value">{{ $paiement->mode_label }}</div>
        </div>
        
        @if($paiement->reference)
        <div class="detail-item">
          <div class="detail-label">Référence:</div>
          <div class="detail-value">{{ $paiement->reference }}</div>
        </div>
        @endif
        
        <div class="detail-item">
          <div class="detail-label">Type de paiement:</div>
          <div class="detail-value">{{ $paiement->account_type_label }}</div>
        </div>
        
        <div class="detail-item">
          <div class="detail-label">Statut:</div>
          <div class="detail-value">
            @if($paiement->statut == 'complet')
              <strong style="color: #4CAF50;">Complet</strong>
            @elseif($paiement->statut == 'partiel')
              <strong style="color: #FF9800;">Partiel</strong>
            @else
              <strong style="color: #F44336;">{{ ucfirst($paiement->statut) }}</strong>
            @endif
          </div>
        </div>
      </div>
      
      @if($paiement->statut != 'annulé')
        <p>Vous pouvez désormais accéder à votre espace personnel pour suivre l'avancement de votre formation.</p>
        
        <p style="text-align: center;">
          <a href="{{ route('uProfile') }}" class="cta-button">
            Accéder à mon espace
          </a>
        </p>
      @endif
      
      <p>Nous vous remercions pour votre confiance et vous souhaitons une excellente expérience de formation.</p>
      
      <p>À très bientôt,</p>
      <p><strong>L'équipe Miko Formation</strong></p>
    </main>
    
    <!-- Pied de page -->
    <footer class="email-footer">
      <p>Miko Formation - Connaissance, Compétence, Excellence</p>
      <div class="footer-links">
        <a href="{{ url('/') }}">Accueil</a> | 
        <a href="{{ route('listing') }}">Formations</a> | 
        <a href="{{ route('contact') }}">Contact</a>
      </div>
    </footer>
  </div>
</body>
</html>