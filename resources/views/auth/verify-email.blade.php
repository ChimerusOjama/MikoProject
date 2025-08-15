<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vérification email | Miko Formation</title>
  <!-- Favicon -->
  <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎓</text></svg>">
  
  <!-- CSS Links -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
</head>
<body class="verification-page">
  <main class="verification-container py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
          <div class="verification-card card shadow">
            <div class="card-body p-4 p-md-5 verification-content">

              <!-- <div class="verification-card-logo">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="80" height="80">
                  <circle cx="50" cy="50" r="45" fill="#3F51B5" />
                  <text x="50" y="58" font-family="Arial" font-size="40" fill="#FFCA28" text-anchor="middle">M</text>
                </svg>
              </div> -->
              
              <h2 class="verification-title">Vérification de votre email</h2>
              <p class="verification-description">Avant de continuer, merci de vérifier votre adresse email</p>
              
              <div class="verification-message">
                <p>
                  Pour accéder à toutes les fonctionnalités de Miko Formation, nous avons besoin de vérifier votre adresse email. 
                  Veuillez cliquer sur le lien que nous venons de vous envoyer par email. 
                  Si vous n'avez pas reçu l'email, cliquez sur le bouton ci-dessous pour en recevoir un nouveau.
                </p>
              </div>

              @if (session('status') == 'verification-link-sent')
                <div class="verification-success">
                  <i class="fas fa-check-circle me-2"></i>
                  Un nouveau lien de vérification a été envoyé à l'adresse email associée à votre compte.
                </div>
              @endif

              <div class="verification-buttons">
                <form method="POST" action="{{ route('verification.send') }}">
                  @csrf
                  <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-paper-plane me-2"></i> Renvoyer l'email de vérification
                  </button>
                </form>

                <form method="POST" action="{{ route('logout-user') }}">
                  @csrf
                  <button type="submit" class="verification-logout">
                    <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  
  <!-- Back to Top -->
  <a href="#" class="back-to-top" id="backToTop"><i class="fas fa-arrow-up"></i></a>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Animation pour le bouton "Back to top"
    document.addEventListener('DOMContentLoaded', function() {
      const backToTop = document.getElementById('backToTop');
      
      window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
          backToTop.classList.add('active');
        } else {
          backToTop.classList.remove('active');
        }
      });
      
      backToTop.addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({top: 0, behavior: 'smooth'});
      });
    });
  </script>
</body>
</html>