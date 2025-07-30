<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Tableau de Bord - Miko Formation')</title>
  
  <!-- Polices Google -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('assets/css/adStyles.css') }}">

  <!-- Favicon -->
  <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎓</text></svg>">
  
  @stack('styles')
</head>
<body>
  <!-- Loader Overlay -->
  <div class="loader-overlay">
    <div class="loader"></div>
  </div>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-header">
      <a href="{{ route('uAdmin') }}" class="logo">
        <i class="fas fa-graduation-cap"></i>
        <span>Miko Formation</span>
      </a>
    </div>
    
    <div class="sidebar-menu">
      <div class="nav-item @yield('dashboard')">
        <a href="{{ route('uAdmin') }}">
          <i class="fas fa-tachometer-alt"></i>
          <span>Tableau de Bord</span>
        </a>
      </div>
      <div class="nav-item @yield('forms')">
        <a href="{{route('uFormation')}}">
          <i class="fas fa-book-open"></i>
          <span>Mes Formations</span>
        </a>
      </div>
      <div class="nav-item @yield('profile')">
        <a href="{{ route('uProfile') }}">
          <i class="fas fa-user"></i>
          <span>Mon Profil</span>
        </a>
      </div>
      <div class="nav-item @yield('support')">
        <a href="{{ route('uSupport') }}">
          <i class="fas fa-question-circle"></i>
          <span>Support</span>
        </a>
      </div>
      <div class="nav-item">
        <a href="{{ route('home') }}">
          <i class="fas fa-home"></i>
          <span>Accueil</span>
        </a>
      </div>
    </div>
  </div>
  
  <!-- Main Content -->
  <div class="main-content">
    <!-- Header -->
    <div class="header">
      <div>
        <h1 class="page-title">@yield('page-title', 'Tableau de Bord')</h1>
        <ul class="breadcrumb">
          @yield('breadcrumb')
        </ul>
      </div>
      
      <div class="header-right">
        <div class="search-bar">
          <i class="fas fa-search"></i>
          <input type="text" placeholder="Rechercher...">
        </div>

        <!-- Notifications et profil utilisateur -->
        <div class="notifications">
          <i class="fas fa-message"></i>
          <span class="notification-badge">1</span>
        </div>
        
        <div class="notifications">
          <i class="fas fa-bell"></i>
          <span class="notification-badge">3</span>
        </div>
        
        <div class="user-profile dropdown">
          <button class="profile-link dropdown-toggle" aria-haspopup="true" aria-expanded="false">
            @if(Auth::user()->avatar)
              <img src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                   alt="Photo de profil" 
                   class="profile-img">
            @else
              <div class="profile-img" style="background: #3F51B5; color: white; display: flex; align-items: center; justify-content: center;">
                {{ substr(Auth::user()->first_name, 0, 1) }}
              </div>
            @endif
            <span class="user-name">{{ Auth::user()->first_name }}</span>
            <i class="fas fa-chevron-down"></i>
          </button>
          <div class="dropdown-menu">
            <a href="" class="dropdown-item">
              <i class="fas fa-user"></i> Mon Profil
            </a>
            <form action="{{ route('logout-user') }}" method="POST">
              @csrf
              <button type="submit" class="dropdown-item logout" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                <i class="fas fa-sign-out-alt"></i> Se déconnecter
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Content -->
    <div class="content">
      @yield('content')
    </div>
  </div>

  <!-- Back to Top Button -->
  <button id="backToTop" class="back-to-top" aria-label="Retour en haut de page">
    <i class="fas fa-arrow-up"></i>
  </button>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('assets/js/adMain.js') }}"></script>
  @stack('scripts')
</body>
</html>