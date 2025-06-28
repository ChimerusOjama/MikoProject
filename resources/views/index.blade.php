@extends('layouts.app')

@section('title', 'Accueil | Miko Formation')

@section('meta')
  <meta name="description" content="MIKO Formation - Centre de formation professionnelle offrant des programmes adaptés aux besoins du marché.">
  <meta name="keywords" content="formation, professionnel, compétences, Congo, Miko Formation, cours, apprentissage, développement personnel">
  <meta name="author" content="Miko Formation">
  <meta property="og:title" content="MIKO Formation - Centre de formation professionnelle">
  <meta property="og:description" content="Découvrez nos formations professionnelles adaptées aux besoins du marché congolais.">
  <meta property="og:image" content="{{ asset('assets/imgs/logo.png') }}">
  <meta property="og:url" content="{{ url('/') }}">
  <meta property="og:type" content="website">
  <meta property="og:locale" content="fr_FR">
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
            <img src="{{ asset('assets/imgs/salle.jpg') }}" class="d-block w-100" alt="Formation professionnelle">
            <div class="carousel-caption">
                <h1>Formez-vous aux métiers d'avenir</h1>
                <a href="#formations" class="btn btn-secondary btn-lg mt-3">Découvrir nos formations</a>
            </div>
            </div>
            <div class="carousel-item">
            <img src="{{ asset('assets/imgs/salle2.jpg') }}" class="d-block w-100" alt="Salle de formation">
            <div class="carousel-caption">
                <h1>Se connaitre est un gage de confiance</h1>
                <a href="#formations" class="btn btn-secondary btn-lg mt-3">&Agrave; propos de nous</a>
            </div>
            </div>
            <div class="carousel-item">
            <img src="{{ asset('assets/imgs/salle3.jpg') }}" class="d-block w-100" alt="Équipe pédagogique">
            <div class="carousel-caption">
                <h1>Un accompagnement personnalisé</h1>
                <a href="#contact" class="btn btn-secondary btn-lg mt-3">Nous contacter</a>
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