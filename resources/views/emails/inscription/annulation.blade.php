<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription annulée - Miko Formation</title>
  <style>
    :root {
      --primary: #3F51B5;
      --primary-dark: #303F9F;
      --secondary: #FFCA28;
      --secondary-dark: #FFB300;
      --error: #C0392B;
      --light: #F5F5F5;
      --dark: #212121;
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
      text-align: center;
    }
    
    .email-title {
      color: var(--error);
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
      background: var(--error);
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
    }
    
    .highlight {
      color: var(--error);
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
      <h2 class="email-title">Inscription annulée</h2>
      
      <p>Bonjour {{ $inscription->name }},</p>
      
      <p>Votre inscription à la formation <span class="highlight">"{{ $inscription->choixForm }}"</span> a bien été annulée.</p>
      
      <p>Nous sommes désolés de ne pas pouvoir vous compter parmi nos apprenants cette fois-ci. Si vous changez d'avis, n'hésitez pas à réserver à nouveau votre place.</p>
      
      <a href="{{ route('listing') }}" class="cta-button">Voir toutes nos formations</a>
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