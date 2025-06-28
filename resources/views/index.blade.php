@extends('layouts.app')

@section('title', 'Formations Professionnelles au Congo | Miko Formation - Centre Certifié')

@section('uHome', 'active')

@section('meta')
  <meta name="description" content="Centre de formation certifié à Brazzaville. Formations bureautique, comptabilité et marketing digital avec certification reconnue. Inscription en ligne.">
  <meta name="keywords" content="centre formation Congo, certification professionnelle Brazzaville, cours Excel Congo, formation comptabilité Congo, Miko Formation Brazzaville, diplôme reconnu Congo">
  <meta name="author" content="Miko Formation">
  <meta name="copyright" content="Miko Formation">
  <meta name="robots" content="index, follow">
  
  <!-- Balises Open Graph -->
  <meta property="og:title" content="Centre de Formation Professionnelle | Miko Formation Congo">
  <meta property="og:description" content="+500 professionnels formés depuis 2020. Formations certifiantes en bureautique, gestion et développement personnel.">
  <meta property="og:image" content="{{ asset('assets/imgs/og-accueil.jpg') }}">
  <meta property="og:url" content="{{ url('/') }}">
  <meta property="og:type" content="website">
  <meta property="og:locale" content="fr_FR">
  <meta property="og:site_name" content="Miko Formation">
  
  <!-- Balises Twitter -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Formez-vous aux métiers d'avenir au Congo">
  <meta name="twitter:description" content="Certifications reconnues par l'État congolais | Formations finançables | 95% de satisfaction">
  <meta name="twitter:image" content="{{ asset('assets/imgs/twitter-accueil.jpg') }}">
  
  <!-- Géolocalisation -->
  <meta name="geo.region" content="CG-BZV">
  <meta name="geo.placename" content="Brazzaville">
  <meta name="geo.position" content="-4.263369;15.242885">
  <meta name="ICBM" content="-4.263369, 15.242885">
  
  <!-- Balises Schema.org -->
  <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "EducationalOrganization",
        "name": "Miko Formation",
        "description": "Centre de formation professionnelle certifié à Brazzaville",
        "address": {
        "@type": "PostalAddress",
        "streetAddress": "123 Avenue de la Formation",
        "addressLocality": "Brazzaville",
        "postalCode": "BZV 123",
        "addressCountry": "CG"
        },
        "telephone": "+242-068552497",
        "image": "{{ asset('assets/imgs/logo.png') }}",
        "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.9",
        "ratingCount": "127"
        },
        "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
        "opens": "08:00",
        "closes": "18:00"
        }
    }
  </script>
@endsection

@section('content')
    <!-- Hero Carousel -->
    <section class="hero-carousel">
        <div id="mainCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="{{ asset('assets/imgs/salle.jpg') }}" 
                alt="Centre de formation professionnelle à Brazzaville" 
                itemprop="contentUrl">
            <div class="carousel-caption">
                <h1>Formez-vous aux métiers d'avenir</h1>
                <p class="lead d-none d-md-block" itemprop="description">Certifications reconnues par l'État congolais</p>
                <a href="{{ route('listing') }}" class="btn btn-secondary btn-lg mt-3">Découvrir nos formations</a>
            </div>
            </div>
            <div class="carousel-item">
            <img src="{{ asset('assets/imgs/salle2.jpg') }}" 
                alt="Centre de formation professionnelle à Brazzaville" 
                itemprop="contentUrl">
            <div class="carousel-caption">
                <h1>Se connaitre est un gage de confiance</h1>
                <p class="lead d-none d-md-block" itemprop="description">Découvrez nos objectifs, notre vision et plus encore</p>
                <a href="{{ route('about') }}" class="btn btn-secondary btn-lg mt-3">&Agrave; propos de nous</a>
            </div>
            </div>
            <div class="carousel-item">
            <img src="{{ asset('assets/imgs/salle3.jpg') }}" 
                alt="Centre de formation professionnelle à Brazzaville" 
                itemprop="contentUrl">
            <div class="carousel-caption">
                <h1>Un accompagnement personnalisé</h1>
                <p class="lead d-none d-md-block" itemprop="description">Nos formateurs vous guident vers la réussite</p>
                <a href="{{ route('contact') }}" class="btn btn-secondary btn-lg mt-3">Nous contacter</a>
            </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Précédent</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Suivant</span>
        </button>
        </div>
    </section>
    <!-- About section -->
    <section class="about-section bg-light py-5">
        <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
            <img src="assets/imgs/about-img.jpg" alt="Équipe Miko Formation" class="img-fluid rounded shadow">
            </div>
            <div class="col-lg-6">
            <h2 class="section-title text-start">Qui sommes-nous ?</h2>
            <p class="lead">Miko Formation est un centre de formation professionnelle engagé dans l'épanouissement par le savoir.</p>
            <p>Fondé en 2020, nous nous sommes donnés pour mission de rendre accessible des formations qualitatives adaptées aux besoins du marché congolais.</p>
            
            <div class="about-features mt-4">
                <div class="d-flex mb-3">
                <div class="me-3 text-primary">
                    <i class="fas fa-graduation-cap fa-2x"></i>
                </div>
                <div>
                    <h4>Notre vision</h4>
                    <p>Devenir le référent de la formation professionnelle en République du Congo.</p>
                </div>
                </div>
                
                <div class="d-flex mb-3">
                <div class="me-3 text-primary">
                    <i class="fas fa-bullseye fa-2x"></i>
                </div>
                <div>
                    <h4>Notre mission</h4>
                    <p>Offrir des parcours complets menant à une réelle expertise professionnelle.</p>
                </div>
                </div>
            </div>
            
            <a href="about.html" class="btn btn-primary mt-3">En savoir plus</a>
            </div>
        </div>
        </div>
    </section>
    <!-- Popular Courses -->
    <section id="formations" class="section popular-courses">
        <div class="container">
        <div class="section-title">
            <h2>Nos formations populaires</h2>
            <p class="lead">Découvrez nos programmes les plus demandés</p>
        </div>

        <div class="scroll-container">
            <div class="courses-scroll" id="coursesScroll">
                <!-- Course 1 -->
                @foreach($forms as $form)
                <div class="scroll-item">
                    <div class="course-card">
                        <img src="{{ $form->image }}" class="course-img" alt="{{ $form->title }}">
                        <div class="course-body">
                            <h3 class="course-title">{{ $form->libForm }}</h3>
                            <div class="course-price">40 000 FCFA</div>
                            <div class="course-meta">
                            <p><i class="fas fa-clock text-primary me-2"></i> Durée : 3 mois</p>
                            <p><i class="fas fa-calendar-alt text-primary me-2"></i> Horaires : 8h-10h / 13h-15h</p>
                            <p><i class="fas fa-money-bill-wave text-primary me-2"></i> 200 000 FCFA</p>
                            </div>
                            <a href="/Reserver_votre_place/form={{ $form->id }}" class="btn btn-primary w-100">Détails</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- <div class="scroll-controls">
            <button class="scroll-btn" id="scrollLeft" aria-label="Défiler vers la gauche">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="scroll-btn" id="scrollRight" aria-label="Défiler vers la droite">
                <i class="fas fa-chevron-right"></i>
            </button>
            </div> -->
        </div>

        <div class="view-all-btn">
            <a href="listing.html" class="btn btn-outline-primary">Voir toutes nos formations</a>
        </div>
        </div>
    </section>
    <!-- Contact -->
    <section class="contact-section py-5 bg-white">
        <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
            <h2 class="section-title">Contactez-nous</h2>
            <p class="lead mb-5">Une question ? Un projet ? Écrivez-nous et nous vous répondrons sous 24h.</p>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <form id="contactForm" class="contact-form">
                    <div class="mb-4">
                    <input type="text" class="form-control" id="name" placeholder="Votre nom complet" required>
                    </div>
                    
                    <div class="mb-4">
                    <input type="email" class="form-control" id="email" placeholder="Votre email" required>
                    </div>
                    
                    <div class="mb-4">
                    <input type="tel" class="form-control" id="phone" placeholder="Votre téléphone (optionnel)">
                    </div>
                    
                    <div class="mb-4">
                    <select class="form-select" id="subject" required>
                        <option value="" disabled selected>Sujet de votre message</option>
                        <option value="formation">Demande d'information sur une formation</option>
                        <option value="partenariat">Partenariat entreprise</option>
                        <option value="autre">Autre demande</option>
                    </select>
                    </div>
                    
                    <div class="mb-4">
                    <textarea class="form-control" id="message" rows="5" placeholder="Votre message" required></textarea>
                    </div>
                    
                    <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane me-2"></i> Envoyer le message
                    </button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </section>
@endsection

@push('scripts')
  <script>
    // Scripts spécifiques à la page d'accueil
    document.addEventListener('DOMContentLoaded', function() {
      // Initialisation du carousel
      const carousel = new bootstrap.Carousel('#mainCarousel', {
        interval: 5000
      });
    });
  </script>
@endpush