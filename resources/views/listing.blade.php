@extends('layouts.app')

@section('title', 'Formations Professionnelles au Congo | Miko Formation')

@section('listing', 'active')

@section('meta')
  <!-- Meta Description optimisée -->
  <meta name="description" content="Découvrez nos formations certifiantes à Brazzaville : bureautique, comptabilité, marketing digital. Inscriptions ouvertes toute l'année.">
  
  <!-- Keywords améliorées -->
  <meta name="keywords" content="formation professionnelle Congo, certification Brazzaville, cours comptabilité, formation informatique, Miko Formation Congo, apprentissage métiers, développement compétences">
  
  <!-- Auteur et droits -->
  <meta name="author" content="Miko Formation">
  <meta name="copyright" content="Miko Formation">
  
  <!-- Balises Open Graph -->
  <meta property="og:title" content="Formations Certifiantes à Brazzaville | Miko Formation">
  <meta property="og:description" content="Formez-vous aux métiers porteurs avec nos programmes professionnels au Congo. Diplômes reconnus par l'État.">
  <meta property="og:image" content="{{ asset('assets/imgs/og-formations.jpg') }}">
  <meta property="og:url" content="{{ route('listing') }}">
  <meta property="og:type" content="website">
  <meta property="og:locale" content="fr_FR">
  <meta property="og:site_name" content="Miko Formation">
  
  <!-- Balises Twitter -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Formations Professionnelles au Congo">
  <meta name="twitter:description" content="+500 étudiants formés depuis 2020 | Bureautique, Gestion, Langues">
  <meta name="twitter:image" content="{{ asset('assets/imgs/twitter-formations.jpg') }}">
  
  <!-- Géolocalisation -->
  <meta name="geo.region" content="CG-BZV">
  <meta name="geo.placename" content="Brazzaville">
  <meta name="geo.position" content="-4.263369;15.242885">
  <meta name="ICBM" content="-4.263369, 15.242885">
  
  <!-- Balises Schema.org -->
  <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "TrainingCenter",
        "name": "Miko Formation",
        "address": {
        "@type": "PostalAddress",
        "streetAddress": "123 Avenue de la Formation",
        "addressLocality": "Brazzaville",
        "postalCode": "BZV 123",
        "addressCountry": "CG"
        },
        "telephone": "+242-068552497",
        "currenciesAccepted": "FCFA",
        "priceRange": "40000-100000 FCFA",
        "openingHours": "Mo-Fr 08:00-18:00",
        "image": "{{ asset('assets/imgs/logo.png') }}"
    }
  </script>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="formations-hero bg-primary text-white py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="display-4 fw-bold mb-3">Formations Professionnelles Certifiantes</h1>
                    <p class="lead">Bureautique, Comptabilité, Marketing Digital - Diplômes reconnus par l'État</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Filtres et tri -->
    <section class="filters-section py-4 bg-light">
        <div class="container">
        <div class="row g-3">
            <div class="col-md-4">
            <select class="form-select" id="categoryFilter">
                <option value="">Toutes les catégories</option>
                <option value="informatique">Informatique</option>
                <option value="gestion">Gestion</option>
                <option value="langues">Langues</option>
            </select>
            </div>
            <div class="col-md-4">
            <select class="form-select" id="levelFilter">
                <option value="">Tous les niveaux</option>
                <option value="debutant">Débutant</option>
                <option value="intermediaire">Intermédiaire</option>
                <option value="avance">Avancé</option>
            </select>
            </div>
            <div class="col-md-4">
            <select class="form-select" id="sortBy">
                <option value="default">Trier par</option>
                <option value="price-asc">Prix croissant</option>
                <option value="price-desc">Prix décroissant</option>
                <option value="duration">Durée</option>
            </select>
            </div>
        </div>
        </div>
    </section>

    <!-- Listing des formations -->
    <section class="formations-list py-5">
        <div class="container">
        <div class="row g-4" id="formationsContainer">
            <!-- Formation 1 -->
            @foreach($forms as $form)
            <div class="col-lg-4 col-md-6" data-category="informatique" data-level="debutant" data-price="150000" data-duration="3">
                <div class="card formation-card h-100 border-0 shadow-sm">
                    <img src="{{ $form->image }}" 
                        alt="Formation {{ $form->libForm }} à Brazzaville - Miko Formation"
                        class="card-img-top">
                    <div class="course-body">
                        <h3 class="course-title" itemprop="name">{{ $form->libForm }}</h3>
                        <div class="course-price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                            <span itemprop="priceCurrency" content="XAF">40 000 FCFA</span>
                            <meta itemprop="price" content="40000">
                        </div>
                        <div class="course-meta">
                            <p itemprop="timeRequired"><i class="fas fa-clock text-primary me-2"></i> Durée : 3 mois</p>
                        </div>
                        <a href="/Reserver_votre_place/form={{ $form->id }}" class="btn btn-primary w-100" itemprop="url">Détails</a>
                    </div>
                </div>
                <!-- Pour le SEO -->
                <script type="application/ld+json">
                    {
                        "@context": "https://schema.org",
                        "@type": "Course",
                        "name": "{{ $form->libForm }}",
                        "description": "{{ Str::limit($form->desc, 100) }}",
                        "provider": {
                            "@type": "Organization",
                            "name": "Miko Formation",
                            "sameAs": "https://mikoformation.cg"
                        },
                        "offers": {
                            "@type": "Offer",
                            "price": "40000",
                            "priceCurrency": "FCFA"
                        }
                    }
                </script>
            </div>
            @endforeach

            <!-- Formation 2 -->
            <div class="col-lg-4 col-md-6" data-category="gestion" data-level="intermediaire" data-price="200000" data-duration="4">
                <div class="card formation-card h-100 border-0 shadow-sm">
                    <img src="assets/imgs/cours1.jpg" class="card-img-top" alt="Formation Comptabilité">
                    <div class="card-body">
                    <span class="badge bg-success mb-2">Gestion</span>
                    <h3 class="h5 card-title">Comptabilité Générale</h3>
                    <p class="card-text">Apprenez les fondamentaux de la comptabilité pour petites entreprises.</p>
                    <ul class="list-unstyled">
                        <li class="mb-1"><i class="fas fa-clock text-primary me-2"></i> 4 mois</li>
                        <li class="mb-1"><i class="fas fa-user-graduate text-primary me-2"></i> Intermédiaire</li>
                        <li class="mb-3"><i class="fas fa-money-bill-wave text-primary me-2"></i> 200 000 FCFA</li>
                    </ul>
                    <a href="#" class="btn btn-primary w-100">Inscription</a>
                    </div>
                </div>
            </div>
            <!-- Formation 2 -->
            <div class="col-lg-4 col-md-6" data-category="gestion" data-level="intermediaire" data-price="200000" data-duration="4">
                <div class="card formation-card h-100 border-0 shadow-sm">
                    <img src="assets/imgs/cours1.jpg" class="card-img-top" alt="Formation Comptabilité">
                    <div class="card-body">
                    <span class="badge bg-success mb-2">Gestion</span>
                    <h3 class="h5 card-title">Comptabilité Générale</h3>
                    <p class="card-text">Apprenez les fondamentaux de la comptabilité pour petites entreprises.</p>
                    <ul class="list-unstyled">
                        <li class="mb-1"><i class="fas fa-clock text-primary me-2"></i> 4 mois</li>
                        <li class="mb-1"><i class="fas fa-user-graduate text-primary me-2"></i> Intermédiaire</li>
                        <li class="mb-3"><i class="fas fa-money-bill-wave text-primary me-2"></i> 200 000 FCFA</li>
                    </ul>
                    <a href="#" class="btn btn-primary w-100">Détails & Inscription</a>
                    </div>
                </div>
            </div>
            <!-- Formation 2 -->
            <div class="col-lg-4 col-md-6" data-category="gestion" data-level="intermediaire" data-price="200000" data-duration="4">
                <div class="card formation-card h-100 border-0 shadow-sm">
                    <img src="assets/imgs/cours1.jpg" class="card-img-top" alt="Formation Comptabilité">
                    <div class="card-body">
                    <span class="badge bg-success mb-2">Gestion</span>
                    <h3 class="h5 card-title">Comptabilité Générale</h3>
                    <p class="card-text">Apprenez les fondamentaux de la comptabilité pour petites entreprises.</p>
                    <ul class="list-unstyled">
                        <li class="mb-1"><i class="fas fa-clock text-primary me-2"></i> 4 mois</li>
                        <li class="mb-1"><i class="fas fa-user-graduate text-primary me-2"></i> Intermédiaire</li>
                        <li class="mb-3"><i class="fas fa-money-bill-wave text-primary me-2"></i> 200 000 FCFA</li>
                    </ul>
                    <a href="#" class="btn btn-primary w-100">Détails & Inscription</a>
                    </div>
                </div>
            </div>
            <!-- Formation 2 -->
            <div class="col-lg-4 col-md-6" data-category="gestion" data-level="intermediaire" data-price="200000" data-duration="4">
                <div class="card formation-card h-100 border-0 shadow-sm">
                    <img src="assets/imgs/cours1.jpg" class="card-img-top" alt="Formation Comptabilité">
                    <div class="card-body">
                    <span class="badge bg-success mb-2">Gestion</span>
                    <h3 class="h5 card-title">Comptabilité Générale</h3>
                    <p class="card-text">Apprenez les fondamentaux de la comptabilité pour petites entreprises.</p>
                    <ul class="list-unstyled">
                        <li class="mb-1"><i class="fas fa-clock text-primary me-2"></i> 4 mois</li>
                        <li class="mb-1"><i class="fas fa-user-graduate text-primary me-2"></i> Intermédiaire</li>
                        <li class="mb-3"><i class="fas fa-money-bill-wave text-primary me-2"></i> 200 000 FCFA</li>
                    </ul>
                    <a href="#" class="btn btn-primary w-100">Détails & Inscription</a>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection