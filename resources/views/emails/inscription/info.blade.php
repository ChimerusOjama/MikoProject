<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pré-inscription confirmée - Miko Formation</title>
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
    
    .options-container {
      background: var(--light);
      border-left: 4px solid var(--secondary);
      padding: 20px;
      margin: 25px 0;
      border-radius: 0 8px 8px 0;
    }
    
    .option-step {
      display: flex;
      margin-bottom: 15px;
    }
    
    .option-number {
      background: var(--primary);
      color: var(--white);
      width: 30px;
      height: 30px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
      flex-shrink: 0;
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
      <h2 class="email-title">Merci pour votre inscription, {{ $inscription->name }} 🎓</h2>
      
      <p>Nous avons bien reçu votre demande d'inscription à la formation :</p>
      <p><strong class="highlight">{{ $inscription->choixForm }}</strong></p>
      
      <p>Celle-ci est actuellement considérée comme une <strong>pré-inscription</strong>, équivalente à une réservation de place.</p>
      
      <p>Pour confirmer définitivement votre inscription, deux options s'offrent à vous :</p>
      
      <div class="options-container">
        <div class="option-step">
          <div class="option-number">1</div>
          <div>
            <strong>Vous présenter en personne</strong> dans nos locaux pour régler les frais d'inscription 
            (montant : <strong>{{ $inscription->montant }}</strong>) <u>dans un délai de 14 jours</u> suivant votre pré-inscription.
          </div>
        </div>
        
        <div class="option-step">
          <div class="option-number">2</div>
          <div>
            <strong>Effectuer le paiement en ligne</strong> directement via notre plateforme (plus rapide et sécurisé).
          </div>
        </div>
      </div>
      
      @if(isset($lienPaiement))
          <p>
              <a href="{{ $lienPaiement }}" style="display:inline-block;padding:10px 20px;background-color:#4CAF50;color:white;text-decoration:none;border-radius:5px;">
                  💳 Payer en ligne maintenant
              </a>
          </p>
      @endif

      
      <p>Nous vous remercions de votre intérêt et restons disponibles pour toute information complémentaire.</p>
      
      <p>À très bientôt,</p>
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
    </footer>
  </div>
</body>
</html>