<!DOCTYPE html>
<html lang="fr">
<head>
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
    
    /* Correction de l'en-tête - alignement centré */
    .email-header {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      padding: 25px 30px;
      color: var(--white);
      text-align: center; /* Centrage ajouté */
    }
    
    .logo {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: 28px;
      margin-bottom: 10px;
      display: block; /* Important pour le centrage */
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
      align-items: flex-start;
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
      font-weight: 600;
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
      text-align: center;
      border: none;
      cursor: pointer;
      font-size: 1rem;
      font-family: inherit;
      width: 100%;
      max-width: 300px;
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
    
    .verification-steps {
      margin-top: 25px;
      background: rgba(63, 81, 181, 0.05);
      border-radius: 8px;
      padding: 20px;
    }
    
    .step-item {
      display: flex;
      margin-bottom: 15px;
      align-items: flex-start;
    }
    
    .step-icon {
      background: var(--primary);
      color: var(--white);
      width: 24px;
      height: 24px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
      flex-shrink: 0;
      font-size: 0.8rem;
    }
    
    .manual-link {
      margin-top: 15px;
      padding: 15px;
      background: var(--light);
      border-radius: 8px;
      font-size: 0.9rem;
    }
    
    .manual-link a {
      color: var(--primary);
      font-weight: 600;
      word-break: break-all;
    }
    
    /* Amélioration de l'alignement du bouton */
    .button-container {
      text-align: center;
      margin: 30px 0;
    }
    
    /* Séparateur stylisé */
    .divider {
      height: 1px;
      background: linear-gradient(90deg, transparent, var(--primary), transparent);
      margin: 30px 0;
    }
  </style>
</head>
<body>
  <div class="email-container">
    <header class="email-header">
      <div class="logo">MIKO<span>FORMATION</span></div>
      <div class="email-date">{{ now()->format('d M Y') }}</div>
    </header>
    
    <main class="email-body">
      <h2 class="email-title">Vérification de votre adresse email</h2>
      
      <!-- Correction : Utilisation de full_name avec emoji -->
      <p>Bonjour <strong>{{ $full_name }}</strong> 👋,</p>
      
      <p>Merci de vous être inscrit sur <strong class="highlight">Miko Formation</strong> ! Pour accéder à toutes les fonctionnalités de notre plateforme, nous avons besoin de vérifier votre adresse email.</p>
      
      <div class="verification-steps">
        <div class="step-item">
          <div class="step-icon">1</div>
          <div>Cliquez sur le bouton ci-dessous pour vérifier votre adresse email</div>
        </div>
        
        <div class="step-item">
          <div class="step-icon">2</div>
          <div>Vous serez automatiquement redirigé vers votre espace personnel</div>
        </div>
      </div>
      
      <div class="button-container">
        <a href="{{ $actionUrl }}" class="cta-button" style="display:inline-block;text-align:center;">
            <i class="fas fa-check-circle" style="margin-right: 8px;"></i> Vérifier mon email
        </a>
      </div>
      
      <div class="manual-link">
        <p>Si vous avez des difficultés avec le bouton, copiez et collez ce lien dans votre navigateur :</p>
        <p><a href="{{ $actionUrl }}">{{ $displayableActionUrl }}</a></p>
      </div>
      
      <div class="divider"></div>
      
      <div style="margin-top: 25px;">
        <p><strong>À noter :</strong> Ce lien de vérification expirera dans 24 heures.</p>
      </div>
      
      <div style="margin-top: 30px;">
        <p>Pour toute question ou assistance, n'hésitez pas à nous contacter :</p>
        <p>
          <i class="fas fa-envelope" style="color: var(--primary); margin-right: 8px;"></i>
          <strong>Email :</strong> support@miko-formation.fr<br>
          
          <i class="fas fa-phone" style="color: var(--primary); margin-right: 8px;"></i>
          <strong>Téléphone :</strong> +33 1 23 45 67 89
        </p>
      </div>
      
      <!-- Correction : Utilisation dynamique de la salutation -->
      <p style="margin-top: 25px;">{{ $salutation }}</p>
    </main>
    
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