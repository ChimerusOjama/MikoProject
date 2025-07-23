<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion | Miko Formation</title>
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
                <h2 class="auth-title">Connexion</h2>
                <p class="text-muted">Accédez à votre espace personnel</p>
              </div>
              
              <!-- Formulaire Laravel -->
              <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                         id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                  @error('email')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                
                <div class="mb-3">
                  <label for="password" class="form-label">Mot de passe</label>
                  <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                         id="password" name="password" required autocomplete="current-password">
                  @error('password')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                  <div class="text-end mt-2">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="small text-primary">
                            Mot de passe oublié ?
                        </a>
                    @endif
                  </div>
                </div>
                
                <div class="form-check mb-4">
                  <input class="form-check-input" type="checkbox" name="remember" 
                         id="remember" {{ old('remember') ? 'checked' : '' }}>
                  <label class="form-check-label" for="remember">Se souvenir de moi</label>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 btn-lg">
                  <i class="fas fa-sign-in-alt me-2"></i> Se connecter
                </button>
                
                <div class="text-center mt-4">
                  <p class="mb-0">Vous n'avez pas de compte ? 
                    <a href="{{ route('register') }}" class="text-primary fw-bold">S'inscrire</a>
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