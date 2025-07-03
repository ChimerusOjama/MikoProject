@extends('layouts.app')

@section('title', 'Inscription à la Formation | Miko Formation')

@section('meta')
    <meta name="description" content="Inscrivez-vous à la formation certifiante {{ $oneForm->libForm }} chez Miko Formation. {{ $oneForm->desc }}">
    <meta name="keywords" content="formation certifiante, formation professionnelle, inscription formation, certification, compétences, Miko Formation, cours en ligne, formation présentielle, formation bureautique, formation informatique">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Inscription à la formation {{ $oneForm->libForm }} | Miko Formation">
    <meta property="og:description" content="Rejoignez une formation certifiante adaptée à votre projet professionnel. Inscription simple, certification reconnue, cours en ligne ou en présentiel.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('assets/imgs/cours1.jpg') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Inscription à une formation certifiante | Miko Formation">
    <meta name="twitter:description" content="Inscrivez-vous à une formation certifiante et boostez votre carrière avec Miko Formation. Certification officielle incluse.">
    <meta name="twitter:image" content="{{ asset('assets/imgs/cours1.jpg') }}">
@endsection

@php($hideLoader = true)
 
@section('content')

  <!-- Modal d'alerte -->
  <x-alert-modal />
  
  <!-- Hero Section spécifique aux détails formation -->
  <section class="formation-detail-hero bg-primary text-white py-5">
    <div class="container text-center">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div class="text-center text-md-start">
          <h1 class="display-4 fw-bold mb-3">Tout est là !</h1>
          <p class="lead">Veuillez lire attentivement les informations relative à cette la formation</p>
        </div>
        <div class="mt-3 mt-md-0">
          <a href="{{ asset('pdfs/emploiDuTemps.pdf') }}" class="btn btn-light download-program-btn" download>
            <i class="fas fa-download me-2"></i>Télécharger l'emploi du temps
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Détails de la formation -->
  <section class="formation-details py-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <div class="formation-content">
            <div class="badge bg-secondary mb-3">Informatique</div>
            
            <div class="formation-summary mb-5">
              <div class="row g-3">
                <div class="col-6 col-md-4">
                  <div class="p-3 border rounded text-center">
                    <i class="fas fa-clock text-primary mb-2 d-block" style="font-size: 1.5rem;"></i>
                    <strong>Durée:</strong> 3 mois
                  </div>
                </div>
                <div class="col-6 col-md-4">
                  <div class="p-3 border rounded text-center">
                    <i class="fas fa-user-graduate text-primary mb-2 d-block" style="font-size: 1.5rem;"></i>
                    <strong>Niveau:</strong> Débutant
                  </div>
                </div>
                <div class="col-6 col-md-4">
                  <div class="p-3 border rounded text-center">
                    <i class="fas fa-money-bill-wave text-primary mb-2 d-block" style="font-size: 1.5rem;"></i>
                    <strong>Prix:</strong> 150 000 FCFA
                  </div>
                </div>
                <div class="col-6 col-md-4">
                  <div class="p-3 border rounded text-center">
                    <i class="fas fa-calendar-alt text-primary mb-2 d-block" style="font-size: 1.5rem;"></i>
                    <strong>Début:</strong> 15 oct. 2024
                  </div>
                </div>
                <div class="col-6 col-md-4">
                  <div class="p-3 border rounded text-center">
                    <i class="fas fa-map-marker-alt text-primary mb-2 d-block" style="font-size: 1.5rem;"></i>
                    <strong>Lieu:</strong> En ligne/Présentiel
                  </div>
                </div>
                <div class="col-6 col-md-4">
                  <div class="p-3 border rounded text-center">
                    <i class="fas fa-certificate text-primary mb-2 d-block" style="font-size: 1.5rem;"></i>
                    <strong>Certification:</strong> Incluse
                  </div>
                </div>
              </div>
            </div>
            
            <h2 class="mb-4">Description complète</h2>
            <p>{{ $oneForm->desc }}.</p>
            
            <h3 class="mb-3">Objectifs de la formation</h3>
            <ul class="mb-4">
              <li>Maîtriser les fonctions avancées d'Excel (rechercheV, index, matrices, etc.)</li>
              <li>Créer et manipuler des tableaux croisés dynamiques complexes</li>
              <li>Automatiser des tâches avec les macros (initiation)</li>
              <li>Concevoir des tableaux de bord professionnels</li>
              <li>Manipuler et analyser de grands volumes de données</li>
            </ul>
            
            <h3 class="mb-3">Programme détaillé</h3>
            <div class="accordion mb-5" id="programAccordion">
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#module1">
                    Module 1 : Fonctions avancées (2 semaines)
                  </button>
                </h2>
                <div id="module1" class="accordion-collapse collapse show" data-bs-parent="#programAccordion">
                  <div class="accordion-body">
                    <ul>
                      <li>Fonctions de recherche avancées (RECHERCHEV, INDEX, EQUIV)</li>
                      <li>Fonctions logiques complexes</li>
                      <li>Traitement des erreurs</li>
                      <li>Fonctions de texte avancées</li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#module2">
                    Module 2 : Analyse de données (3 semaines)
                  </button>
                </h2>
                <div id="module2" class="accordion-collapse collapse" data-bs-parent="#programAccordion">
                  <div class="accordion-body">
                    <ul>
                      <li>Tableaux croisés dynamiques avancés</li>
                      <li>Segmentation de données</li>
                      <li>Graphiques avancés</li>
                      <li>Scénarios et valeur cible</li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#module3">
                    Module 3 : Automatisation (2 semaines)
                  </button>
                </h2>
                <div id="module3" class="accordion-collapse collapse" data-bs-parent="#programAccordion">
                  <div class="accordion-body">
                    <ul>
                      <li>Introduction aux macros</li>
                      <li>Enregistrement de macros simples</li>
                      <li>Boutons de commande</li>
                      <li>Automatisation de rapports</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            
            <h3 class="mb-3">Public cible</h3>
            <p>Professionnels en comptabilité, gestion, administration, analyse de données ou toute personne souhaitant améliorer ses compétences en analyse et présentation de données avec Excel.</p>
            
            <h3 class="mb-3">Prérequis</h3>
            <p>Connaissances de base d'Excel (création de formules simples, mise en forme de base). Un test de niveau sera proposé avant le début de la formation.</p>
          </div>
        </div>
        
        @if(Route::has('login'))
            @auth
              <!-- Formulaire d'inscription -->
              <div class="col-lg-4">
                <div class="registration-form bg-light p-4 rounded shadow-sm sticky-top">
                  <h3 class="h4 mb-4 text-center">Inscription</h3>
                  <form id="formationRegistration" action="{{ route('inscForm') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                      <label for="fullName" class="form-label">Nom complet <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="fullName" disabled value="{{ Auth::user()->name }}" required>
                    </div>
                    <div class="mb-3">
                      <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                      <input type="email" class="form-control" id="email" disabled value="{{ Auth::user()->email }}" required>
                    </div>
                    <div class="mb-3">
                      <label for="phone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                      <input type="tel" class="form-control" id="phone" disabled value="{{ Auth::user()->phone }}" required>
                    </div>
                    <div class="mb-3">
                      <label for="address" class="form-label">Adresse <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="address" disabled value="{{ Auth::user()->address }}" required>
                    </div>
                    <input type="hidden" value="{{ $oneForm->libForm }}" name="choixForm">
                    <div class="mb-4">
                      <label for="message" class="form-label">Message (optionnel)</label>
                      <textarea class="form-control" id="message" rows="3"></textarea>
                    </div>
                    <div class="d-grid">
                      <button type="submit" class="btn btn-primary btn-lg">S'inscrire maintenant</button>
                    </div>
                    <div class="text-center mt-3">
                      <small class="text-muted">En cliquant, vous acceptez nos <a href="#">conditions générales</a></small>
                    </div>
                  </form>
                </div>
              </div>
            @else
              <!-- Auth Required Card -->
              <div class="col-lg-4">
                <div class="auth-required-card bg-light p-4 rounded shadow-sm sticky-top">
                  <div class="text-center">
                    <i class="fas fa-lock fa-3x text-primary mb-3"></i>
                    <h3 class="h4 mb-3">Inscription requise</h3>
                    <p class="mb-4">Pour vous inscrire à cette formation, vous devez avoir un compte. Connectez-vous ou créez un compte gratuitement.</p>
                    
                    <div class="d-grid gap-2">
                      <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                      </a>
                      <a href="{{ route('register') }}" class="btn btn-outline-primary">
                        <i class="fas fa-user-plus me-2"></i>Créer un compte
                      </a>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="benefits">
                      <h4 class="h5 mb-3">Avantages d'un compte :</h4>
                      <ul class="list-unstyled">
                        <li class="mb-2">
                          <i class="fas fa-check-circle text-success me-2"></i>
                          Accès à toutes nos formations
                        </li>
                        <li class="mb-2">
                          <i class="fas fa-check-circle text-success me-2"></i>
                          Suivi de votre progression
                        </li>
                        <li class="mb-2">
                          <i class="fas fa-check-circle text-success me-2"></i>
                          Téléchargement des ressources
                        </li>
                        <li class="mb-2">
                          <i class="fas fa-check-circle text-success me-2"></i>
                          Support prioritaire
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            @endauth
        @endif

      </div>
    </div>
  </section>

  <!-- Section témoignages -->
  <section class="testimonials bg-light py-5">
    <div class="container">
      <h2 class="text-center mb-5">Témoignages d'anciens participants</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="testimonial-card p-4 rounded bg-white shadow-sm h-100">
            <div class="d-flex align-items-center mb-3">
              <img src="{{ asset('assets/imgs/avatar1.webp') }}" class="rounded-circle me-3" width="60" height="60" alt="Témoignage">
              <div>
                <h5 class="mb-0">Jean K.</h5>
                <small>Comptable</small>
              </div>
            </div>
            <p class="mb-0">"Cette formation m'a permis d'automatiser 80% de mes tâches répétitives en comptabilité. Un gain de temps considérable au quotidien."</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="testimonial-card p-4 rounded bg-white shadow-sm h-100">
            <div class="d-flex align-items-center mb-3">
              <img src="{{ asset('assets/imgs/avatar2.webp') }}" class="rounded-circle me-3" width="60" height="60" alt="Témoignage">
              <div>
                <h5 class="mb-0">Marie L.</h5>
                <small>Chargée de projet</small>
              </div>
            </div>
            <p class="mb-0">"Les tableaux de bord que je crée maintenant impressionnent mes supérieurs. La formation est très pratique et concrète."</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="testimonial-card p-4 rounded bg-white shadow-sm h-100">
            <div class="d-flex align-items-center mb-3">
              <img src="{{ asset('assets/imgs/avatar3.webp') }}" class="rounded-circle me-3" width="60" height="60" alt="Témoignage">
              <div>
                <h5 class="mb-0">Paul D.</h5>
                <small>Analyste commercial</small>
              </div>
            </div>
            <p class="mb-0">"J'ai pu mettre en application immédiatement ce que j'ai appris. Le formateur est très pédagogue et disponible."</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Autres formations -->
  <section class="related-formations py-5">
    <div class="container">
      <h2 class="text-center mb-5">Vous pourriez aussi aimer</h2>
      <div class="row g-4">
        <div class="col-md-6 col-lg-4">
          <div class="card formation-card h-100 border-0 shadow-sm">
            <img src="assets/imgs/cours1.jpg" class="card-img-top" alt="Formation Power BI">
            <div class="card-body">
              <span class="badge bg-secondary mb-2">Informatique</span>
              <h3 class="h5 card-title">Power BI</h3>
              <p class="card-text">Créez des tableaux de bord interactifs pour l'analyse commerciale.</p>
              <ul class="list-unstyled">
                <li class="mb-1"><i class="fas fa-clock text-primary me-2"></i> 2 mois</li>
                <li class="mb-1"><i class="fas fa-user-graduate text-primary me-2"></i> Intermédiaire</li>
                <li class="mb-3"><i class="fas fa-money-bill-wave text-primary me-2"></i> 180 000 FCFA</li>
              </ul>
              <a href="#" class="btn btn-outline-primary w-100">Voir détails</a>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="card formation-card h-100 border-0 shadow-sm">
            <img src="assets/imgs/cours1.jpg" class="card-img-top" alt="Formation Access">
            <div class="card-body">
              <span class="badge bg-secondary mb-2">Informatique</span>
              <h3 class="h5 card-title">Access Avancé</h3>
              <p class="card-text">Gérez des bases de données relationnelles pour votre entreprise.</p>
              <ul class="list-unstyled">
                <li class="mb-1"><i class="fas fa-clock text-primary me-2"></i> 3 mois</li>
                <li class="mb-1"><i class="fas fa-user-graduate text-primary me-2"></i> Intermédiaire</li>
                <li class="mb-3"><i class="fas fa-money-bill-wave text-primary me-2"></i> 200 000 FCFA</li>
              </ul>
              <a href="#" class="btn btn-outline-primary w-100">Voir détails</a>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="card formation-card h-100 border-0 shadow-sm">
            <img src="assets/imgs/cours1.jpg" class="card-img-top" alt="Formation Data">
            <div class="card-body">
              <span class="badge bg-secondary mb-2">Informatique</span>
              <h3 class="h5 card-title">Analyse de Données</h3>
              <p class="card-text">Techniques avancées d'analyse et visualisation de données.</p>
              <ul class="list-unstyled">
                <li class="mb-1"><i class="fas fa-clock text-primary me-2"></i> 4 mois</li>
                <li class="mb-1"><i class="fas fa-user-graduate text-primary me-2"></i> Avancé</li>
                <li class="mb-3"><i class="fas fa-money-bill-wave text-primary me-2"></i> 250 000 FCFA</li>
              </ul>
              <a href="#" class="btn btn-outline-primary w-100">Voir détails</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection