<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Miko Formation')</title>
  
  <!-- Meta tags -->
  @yield('meta')
  
  <!-- Favicon -->
  <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎓</text></svg>">
  
  <!-- CSS Links -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
  
  @stack('styles')
</head>
<body>
  <!-- Loader Overlay -->
  @empty($hideLoader)
    <div class="loader-overlay">
      <div class="loader"></div>
    </div>
  @endempty
  <!-- Alert Modals -->
  @empty($hideModals)
    @if(session('success'))
      <script>
          document.addEventListener('DOMContentLoaded', function() {
              const message = {!! json_encode(session('success'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) !!};
              document.getElementById('success-modal-body').innerHTML = `<p>${message}</p>`;
              const modal = new bootstrap.Modal(document.getElementById('successModal'));
              modal.show();
          });
      </script>
    @endif

    @if(session('warning'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const message = {!! json_encode(session('warning'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) !!};
                document.getElementById('warning-modal-body').innerHTML = `<p>${message}</p>`;
                const modal = new bootstrap.Modal(document.getElementById('warningModal'));
                modal.show();
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const message = {!! json_encode(session('error'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) !!};
                document.getElementById('error-modal-body').innerHTML = `<p>${message}</p>`;
                const modal = new bootstrap.Modal(document.getElementById('errorModal'));
                modal.show();
            });
        </script>
    @endif
  @endempty

  <!-- Top Info Bar -->
  <div class="top-info-bar">
    <div class="container">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div class="contact-info">
          <a href="mailto:contact@mikoformation.com"><i class="fas fa-envelope"></i> contact@mikoformation.cg</a>
          <a href="tel:+242068552497"><i class="fas fa-phone-alt"></i> +242 06 855 24 97</a>
        </div>
        <div class="social-links mt-2 mt-md-0">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
    </div>
  </div>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
      <a class="navbar-brand" href="{{ route('home') }}">MIKO<span>FORMATION</span></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link @yield('uHome')" href="{{ route('uHome') }}">Accueil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link @yield('listing')" href="{{ route('listing') }}">Formations</a>
          </li>
          <li class="nav-item">
            <a class="nav-link @yield('contact')" href="{{ route('contact') }}">Contact</a>
          </li>
          <li class="nav-item">
            <a class="nav-link @yield('about')" href="{{ route('about') }}">À propos</a>
          </li>
          
          <!-- Dropdown pour desktop - Connecté -->
          @if(Route::has('login'))
            @auth
              <li class="nav-item dropdown d-lg-block">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                  <i class="fas fa-user-circle me-1"></i> Mon compte
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  @if(auth()->user()->usertype === 'user')
                    <li><a class="dropdown-item login-btn" href="{{ route('uAdmin') }}"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                  @else
                    <li><a class="dropdown-item login-btn" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin</a></li>
                  @endif
                  
                  <form action="{{ auth()->user()->usertype === 'user' ? route('logout-user') : route('aLogout') }}" method="post">
                    @csrf
                    <button type="submit" class="dropdown-item logout-btn"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</button>
                  </form>
                </ul>
              </li>
            @else
              <!-- Boutons pour desktop - Non connecté -->
              <li class="nav-item desktop-auth-links d-none d-lg-flex">
                <a class="nav-link login-btn me-2" href="{{ route('login') }}">
                  <i class="fas fa-sign-in-alt me-1"></i>Se connecter
                </a>
                <a class="nav-link register-btn" href="{{ route('register') }}">
                  <i class="fas fa-user-plus me-1"></i>S'inscrire
                </a>
              </li>
            @endauth
          @endif
        </ul>

<!-- Liens compte pour mobile -->
<div class="mobile-account-links">
  @if(Route::has('login'))
    @auth
      @if(auth()->user()->usertype === 'user')
        <a class="nav-link login-btn" href="{{ route('uAdmin') }}">
          <i class="fas fa-tachometer-alt me-2"></i>Dashboard
        </a>
      @else
        <a class="nav-link login-btn" href="{{ route('admin.dashboard') }}">
          <i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin
        </a>
      @endif
      
      <form action="{{ auth()->user()->usertype === 'user' ? route('logout-user') : route('aLogout') }}" method="post" class="w-100">
        @csrf
        <button type="submit" class="nav-link logout-btn w-100">
          <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
        </button>
      </form>
    @else
      <a class="nav-link login-btn" href="{{ route('login') }}">
        <i class="fas fa-sign-in-alt me-2"></i>Se connecter
      </a>
      <a class="nav-link register-btn" href="{{ route('register') }}">
        <i class="fas fa-user-plus me-2"></i>S'inscrire
      </a>
    @endauth
  @endif
</div>
      </div>
    </div>
  </nav>

  <!-- Contenu principal -->
  <main>
    @yield('content')
  </main>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 mb-4">
          <!-- <h5 class="app-name">MIKO FORMATION</h5> -->
          <h5 class="app-name">{{ config('app.name') }}</h5>
          <p>Centre de formation professionnelle offrant des programmes adaptés aux besoins du marché.</p>
          <div class="mt-4">
            <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
            <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
          </div>
        </div>
        <div class="col-lg-2 col-md-6 mb-4">
          <h5>LIENS RAPIDES</h5>
          <ul class="footer-links">
            <li><a href="{{ route('home') }}">Accueil</a></li>
            <li><a href="{{ route('listing') }}">Formations</a></li>
            <li><a href="{{ route('about') }}">À propos</a></li>
            <li><a href="{{ route('contact') }}">Contact</a></li>
          </ul>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
          <h5>INFORMATIONS</h5>
          <ul class="footer-links">
            <li><a href="#">Politique de confidentialité</a></li>
            <li><a href="#">Conditions générales</a></li>
            <li><a href="#">Mentions légales</a></li>
          </ul>
        </div>
        <div class="col-lg-3 mb-4">
          <h5>CONTACT</h5>
          <div class="footer-contact">
            <p><i class="fas fa-map-marker-alt"></i> 123 Avenue de la Formation, Brazzaville</p>
            <p><i class="fas fa-phone-alt"></i> +242 06 855 24 97</p>
            <p><i class="fas fa-envelope"></i> contact@mikoformation.cg</p>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <p class="mb-0">&copy; {{ date('Y') }} MIKO FORMATION. Tous droits réservés.</p>
      </div>
    </div>
  </footer>

  <!-- Back to Top -->
  <a href="#" class="back-to-top" id="backToTop"><i class="fas fa-arrow-up"></i></a>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>
  
  @stack('scripts')
</body>
</html>