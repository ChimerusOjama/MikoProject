@extends('layouts.app')

@section('title', 'À Propos de Miko Formation | Centre Certifié à Brazzaville')

@section('about', 'active')

@section('meta')
  <!-- Meta Description institutionnelle -->
  <meta name="description" content="Découvrez l'histoire, la mission et l'équipe de Miko Formation. Centre de formation professionnelle certifié à Brazzaville depuis 2015. +5000 diplômés formés.">
  
  <!-- Keywords institutionnelles -->
  <meta name="keywords" content="histoire Miko Formation, équipe formateurs Congo, valeurs centre formation, certification formation Brazzaville, parcours Miko Formation, pédagogie professionnelle Congo">
  
  <!-- Auteur et droits -->
  <meta name="author" content="Miko Formation">
  <meta name="copyright" content="Miko Formation">
  <meta name="robots" content="index, follow">
  
  <!-- Balises Open Graph optimisées -->
  <meta property="og:title" content="Notre Histoire & Valeurs | Miko Formation Congo">
  <meta property="og:description" content="Fondé en 2015, Miko Formation a formé +5000 professionnels au Congo. Découvrez notre équipe pédagogique et notre engagement qualité.">
  <meta property="og:image" content="{{ asset('assets/imgs/og-apropos.jpg') }}">
  <meta property="og:url" content="{{ route('about') }}">
  <meta property="og:type" content="website">
  <meta property="og:locale" content="fr_FR">
  <meta property="og:site_name" content="Miko Formation">
  
  <!-- Balises Twitter -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="L'équipe derrière votre réussite | Miko Formation">
  <meta name="twitter:description" content="+30 formateurs experts | 8 ans d'expérience | Centre de formation certifié ISO 9001">
  <meta name="twitter:image" content="{{ asset('assets/imgs/twitter-apropos.jpg') }}">
  
  <!-- Géolocalisation -->
  <meta name="geo.region" content="CG-BZV">
  <meta name="geo.placename" content="Brazzaville">
  <meta name="geo.position" content="-4.263369;15.242885">
  <meta name="ICBM" content="-4.263369, 15.242885">
  
  <!-- Balises Schema.org enrichies -->
  <script type="application/ld+json">
  [
    {
      "@context": "https://schema.org",
      "@type": "AboutPage",
      "name": "À Propos de Miko Formation",
      "description": "Histoire, mission et équipe du centre de formation professionnelle Miko à Brazzaville",
      "publisher": {
        "@type": "Organization",
        "name": "Miko Formation",
        "logo": {
          "@type": "ImageObject",
          "url": "{{ asset('assets/imgs/logo.png') }}"
        }
      }
    },
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Miko Formation",
      "url": "https://mikoformation.cg",
      "logo": "{{ asset('assets/imgs/logo.png') }}",
      "foundingDate": "2015",
      "founders": [
        {
          "@type": "Person",
          "name": "Michel K."
        }
      ],
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "123 Avenue de la Formation",
        "addressLocality": "Brazzaville",
        "postalCode": "BZV 123",
        "addressCountry": "CG"
      },
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+242-068552497",
        "contactType": "Service client"
      },
      "numberOfEmployees": "30",
      "awards": "Meilleur centre de formation professionnelle 2023",
      "founders": [
        {
          "@type": "Person",
          "name": "Michel K."
        }
      ]
    }
  ]
  </script>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="formations-hero bg-primary text-white py-5">
    <div class="container">
        <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <h1 class="display-4 fw-bold mb-3">À Propos de Miko Formation</h1>
            <p class="lead">Découvrez notre mission, notre histoire et notre engagement pour votre réussite professionnelle</p>
        </div>
        </div>
    </div>
    </section>

  <!-- Mission & Vision Section -->
  <section class="mission-vision animate-section">
    <div class="container">
      <div class="row">
        <h2 class="section-title text-center">Notre Mission & Vision</h2>
        <p class="lead text-center mb-5">Les fondements de notre engagement pour votre réussite</p>
        <div class="col-lg-6 mb-5 mb-lg-0">
          <div class="card h-100 border-0 shadow">
            <div class="card-body p-4 p-md-5">
              <i class="fas fa-bullseye display-1 text-primary mb-4"></i>
              <h3 class="mb-3">Notre Mission</h3>
              <p>Transformer les ambitions professionnelles en réalités tangibles à travers des formations de qualité, accessibles et adaptées aux besoins du marché congolais.</p>
              <p>Nous nous engageons à fournir des programmes pédagogiques innovants qui répondent aux défis économiques actuels et préparent nos apprenants aux métiers de demain.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card h-100 border-0 shadow">
            <div class="card-body p-4 p-md-5">
              <i class="fas fa-binoculars display-1 text-primary mb-4"></i>
              <h3 class="mb-3">Notre Vision</h3>
              <p>Devenir le principal catalyseur du développement des compétences professionnelles en République du Congo, en contribuant activement à la croissance économique et à la réduction du chômage.</p>
              <p>D'ici 2030, nous aspirons à former 10 000 professionnels hautement qualifiés dans des secteurs clés de l'économie congolaise.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Notre Histoire Section -->
  <section class="history-section">
    <div class="container">
      <h2 class="section-title text-center text-white">Notre Histoire</h2>
      <p class="lead text-center mb-5">Un parcours marqué par l'engagement et l'innovation pédagogique</p>
      <!-- event 1 -->
      <div class="timeline">
        <div class="timeline-item">
          <div class="timeline-content">
            <h3>2015 - Fondation</h3>
            <p>Création de Miko Formation avec 2 salles de classe et 3 formateurs. Première promotion de 25 étudiants en informatique et gestion.</p>
          </div>
        </div>
        <!-- event 2 -->
        <div class="timeline-item">
          <div class="timeline-content">
            <h3>2017 - Expansion</h3>
            <p>Ouverture d'un nouveau campus à Pointe-Noire. Lancement de programmes de formation en langues et développement personnel.</p>
          </div>
        </div>
        <!-- event 3 -->
        <div class="timeline-item">
          <div class="timeline-content">
            <h3>2019 - Reconnaissance</h3>
            <p>Accréditation par le Ministère de l'Enseignement Technique. Signature de partenariats avec 15 entreprises locales pour les stages étudiants.</p>
          </div>
        </div>
        <!-- event 4 -->
        <div class="timeline-item">
          <div class="timeline-content">
            <h3>2021 - Innovation</h3>
            <p>Lancement de la plateforme d'apprentissage en ligne. Adaptation des formations aux défis de la digitalisation post-pandémie.</p>
          </div>
        </div>
        <!-- event 5 -->
        <div class="timeline-item">
          <div class="timeline-content">
            <h3>2023 - Leadership</h3>
            <p>Plus de 5 000 diplômés formés. Reconnaissance comme meilleur centre de formation professionnelle au Congo par le magazine "Économie Africaine".</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Nos Valeurs Section -->
  <section class="values-section">
    <div class="container">
      <h2 class="section-title text-center">Nos Valeurs Fondamentales</h2>
      <p class="lead text-center mb-5">Les principes qui guident chacune de nos actions</p>
      
      <div class="row g-4">
        <div class="col-md-6 col-lg-3">
          <div class="value-card">
            <i class="fas fa-graduation-cap"></i>
            <h3>Excellence</h3>
            <p>Nous visons l'excellence dans chaque formation dispensée, avec des programmes constamment actualisés et des formateurs experts.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="value-card">
            <i class="fas fa-hands-helping"></i>
            <h3>Engagement</h3>
            <p>Nous nous engageons pleinement dans la réussite de chaque apprenant, avec un accompagnement personnalisé tout au long du parcours.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="value-card">
            <i class="fas fa-lightbulb"></i>
            <h3>Innovation</h3>
            <p>Nous intégrons constamment les dernières méthodologies pédagogiques et technologies pour un apprentissage efficace et moderne.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="value-card">
            <i class="fas fa-users"></i>
            <h3>Communauté</h3>
            <p>Nous favorisons l'échange et la collaboration entre apprenants, formateurs et entreprises pour créer un écosystème dynamique.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Statistiques Section -->
  <section class="stats-section">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-3 col-6">
          <div class="stat-card">
            <div class="stat-number">8+</div>
            <h4>Années d'expérience</h4>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="stat-card">
            <div class="stat-number">5 000+</div>
            <h4>Diplômés satisfaits</h4>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="stat-card">
            <div class="stat-number">30+</div>
            <h4>Formateurs experts</h4>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="stat-card">
            <div class="stat-number">50+</div>
            <h4>Entreprises partenaires</h4>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Notre Équipe Section -->
  <section class="team-section">
    <div class="container">
      <h2 class="section-title text-center">Rencontrez Notre Équipe</h2>
      <p class="lead text-center mb-5">Des passionnés dédiés à votre réussite</p>
      
      <div class="row g-4">
        <div class="col-md-6 col-lg-3">
          <div class="team-card">
            <div class="team-img">
              <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Directeur">
            </div>
            <div class="team-info">
              <h3 class="h5">Michel K.</h3>
              <p class="text-primary">Fondateur & Directeur</p>
              <p>20 ans d'expérience en pédagogie et management de centres de formation.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="team-card">
            <div class="team-img">
              <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Responsable Pédagogique">
            </div>
            <div class="team-info">
              <h3 class="h5">Sarah N.</h3>
              <p class="text-primary">Responsable Pédagogique</p>
              <p>Docteure en Sciences de l'Éducation, spécialiste des méthodes actives.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="team-card">
            <div class="team-img">
              <img src="https://images.unsplash.com/photo-1544717305-2782549b5136?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Coordinateur Formations">
            </div>
            <div class="team-info">
              <h3 class="h5">David M.</h3>
              <p class="text-primary">Coordinateur Formations</p>
              <p>Expert en ingénierie de formation et développement de compétences.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="team-card">
            <div class="team-img">
              <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Responsable Relations Entreprises">
            </div>
            <div class="team-info">
              <h3 class="h5">Amina T.</h3>
              <p class="text-primary">Relations Entreprises</p>
              <p>Ancienne DRH, connecte nos apprenants avec les meilleures opportunités.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="contact-cta">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
          <h2 class="mb-4">Prêt à transformer votre avenir professionnel ?</h2>
          <p class="lead mb-5">Rejoignez notre communauté d'apprenants et bénéficiez d'un accompagnement sur mesure pour atteindre vos objectifs de carrière.</p>
          <a href="listing.html" class="btn btn-light btn-lg me-2">Découvrir nos formations</a>
          <a href="#" class="btn btn-outline-light btn-lg">Demander un conseil</a>
        </div>
      </div>
    </div>
  </section>
@endsection