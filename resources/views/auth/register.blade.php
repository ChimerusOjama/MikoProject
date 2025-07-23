<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription | Miko Formation</title>
  <!-- Favicon -->
  <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎓</text></svg>">
  
  <!-- CSS Links -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
</head>
<body class="auth-page">
  <main class="auth-container py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
          <div class="auth-card card shadow">
            <div class="card-body p-4 p-md-5">
              <div class="text-center mb-4">
                <h2 class="auth-title">Créer un compte</h2>
                <p class="text-muted">Rejoignez notre communauté d'apprenants</p>
              </div>
              
              <!-- Formulaire Laravel -->
              <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">Prénom</label>
                    <input type="text" class="form-control form-control-lg @error('first_name') is-invalid @enderror" 
                           id="first_name" name="first_name" value="{{ old('first_name') }}" required autofocus>
                    @error('first_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">Nom</label>
                    <input type="text" class="form-control form-control-lg @error('last_name') is-invalid @enderror" 
                           id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                    @error('last_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>
                </div>
                
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                         id="email" name="email" value="{{ old('email') }}" required>
                  @error('email')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                
                <div class="mb-3">
                  <label for="phone" class="form-label">Téléphone</label>
                  <input type="tel" class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                         id="phone" name="phone" value="{{ old('phone') }}">
                  @error('phone')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="address" class="form-label">Adresse</label>
                  <input type="text" class="form-control form-control-lg @error('address') is-invalid @enderror" 
                         id="address" name="address" value="{{ old('address') }}">
                  @error('address')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                
                <div class="mb-3">
                  <label for="password" class="form-label">Mot de passe</label>
                  <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                         id="password" name="password" required>
                  @error('password')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                
                <div class="mb-4">
                  <label for="password-confirm" class="form-label">Confirmer le mot de passe</label>
                  <input type="password" class="form-control form-control-lg" 
                         id="password-confirm" name="password_confirmation" required>
                </div>
                
                <div class="form-check mb-4">
                  <input class="form-check-input @error('terms') is-invalid @enderror" 
                         type="checkbox" id="terms" name="terms" required>
                  <label class="form-check-label" for="terms">
                    J'accepte les <a href="#" class="text-primary">conditions d'utilisation</a> 
                    et la <a href="#" class="text-primary">politique de confidentialité</a>
                  </label>
                  @error('terms')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                
                <button type="submit" class="btn btn-primary w-100 btn-lg">
                  <i class="fas fa-user-plus me-2"></i> S'inscrire
                </button>
                
                <div class="text-center mt-4">
                  <p class="mb-0">Vous avez déjà un compte ? 
                    <a href="{{ route('login') }}" class="text-primary fw-bold">Se connecter</a>
                  </p>
                </div>
              </form>
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
  <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>