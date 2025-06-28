@extends('layouts.app')

@section('title', 'Contactez Miko Formation à Brazzaville | Centre de Formation Professionnelle')

@section('contact', 'active')

@section('meta')
  <!-- Meta Description locale -->
  <meta name="description" content="Contactez notre centre de formation à Brazzaville. Demande d'info, partenariat ou inscription : +242 06 855 24 97 | Ouvert du lundi au vendredi de 8h à 18h.">
  
  <!-- Keywords géolocalisées -->
  <meta name="keywords" content="contact formation Brazzaville, adresse Miko Formation Congo, téléphone centre formation, email contact formation, localisation centre formation Congo">
  
  <!-- Auteur et droits -->
  <meta name="author" content="Miko Formation">
  <meta name="copyright" content="Miko Formation">
  <meta name="robots" content="index, follow">
  
  <!-- Balises Open Graph locales -->
  <meta property="og:title" content="Contact & Localisation | Miko Formation Brazzaville">
  <meta property="og:description" content="Visitez notre centre de formation au 123 Avenue de la Formation à Brazzaville. Contact : +242 06 855 24 97 | contact@mikoformation.cg">
  <meta property="og:image" content="{{ asset('assets/imgs/og-contact.jpg') }}">
  <meta property="og:url" content="{{ route('contact') }}">
  <meta property="og:type" content="website">
  <meta property="og:locale" content="fr_FR">
  
  <!-- Balises Twitter -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Contactez-nous | Miko Formation">
  <meta name="twitter:description" content="Service client réactif 7j/7 | Centre ouvert du lundi au vendredi de 8h à 18h | Réponses sous 24h">
  <meta name="twitter:image" content="{{ asset('assets/imgs/twitter-contact.jpg') }}">
  
  <!-- Géolocalisation précise -->
  <meta name="geo.region" content="CG-BZV">
  <meta name="geo.placename" content="Brazzaville">
  <meta name="geo.position" content="-4.263369;15.242885">
  <meta name="ICBM" content="-4.263369, 15.242885">
  
  <!-- Balises Schema.org pour LocalBusiness -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "ContactPage",
    "name": "Contact Miko Formation",
    "description": "Page de contact du centre de formation professionnelle Miko à Brazzaville",
    "publisher": {
      "@type": "Organization",
      "name": "Miko Formation",
      "logo": {
        "@type": "ImageObject",
        "url": "{{ asset('assets/imgs/logo.png') }}"
      }
    }
  }
  </script>
  
  <!-- Données structurées LocalBusiness -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "Miko Formation",
    "image": "{{ asset('assets/imgs/logo.png') }}",
    "@id": "https://mikoformation.cg",
    "url": "https://mikoformation.cg",
    "telephone": "+242-068552497",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "123 Avenue de la Formation",
      "addressLocality": "Brazzaville",
      "postalCode": "BZV 123",
      "addressCountry": "CG"
    },
    "geo": {
      "@type": "GeoCoordinates",
      "latitude": "-4.263369",
      "longitude": "15.242885"
    },
    "openingHoursSpecification": {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": [
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday"
      ],
      "opens": "08:00",
      "closes": "18:00"
    },
    "sameAs": [
      "https://www.facebook.com/MikoFormation",
      "https://www.linkedin.com/company/mikoformation"
    ]
  }
  </script>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="formations-hero bg-primary text-white py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="display-4 fw-bold mb-3">Contactez-nous !</h1>
                    <p class="lead">Nous sommes à votre écoute, parlez-nous à coeur ouvert</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Contact -->
    <section class="contact-section py-5" itemscope itemtype="https://schema.org/LocalBusiness">
        <div class="container">
            <div class="row g-4">
                <!-- Formulaire de contact -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h2 class="h4 mb-4">Envoyez-nous un message</h2>
                            <form id="contactForm" novalidate>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom complet</label>
                                    <input type="text" class="form-control" id="name" name="name" required placeholder="Votre nom">
                                    <div class="invalid-feedback">Veuillez entrer votre nom.</div>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Adresse e-mail</label>
                                    <input type="email" class="form-control" id="email" name="email" required placeholder="Votre e-mail">
                                    <div class="invalid-feedback">Veuillez entrer une adresse e-mail valide.</div>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Votre numéro">
                                </div>
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Sujet</label>
                                    <select class="form-select" id="subject" name="subject" required>
                                        <option value="" disabled selected>Choisissez un sujet</option>
                                        <option value="info">Demande d'information</option>
                                        <option value="inscription">Inscription à une formation</option>
                                        <option value="partenariat">Demande de partenariat</option>
                                        <option value="autre">Autre demande</option>
                                    </select>
                                    <div class="invalid-feedback">Veuillez sélectionner un sujet.</div>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control" id="message" name="message" rows="5" required placeholder="Votre message"></textarea>
                                    <div class="invalid-feedback">Veuillez écrire votre message.</div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Envoyer</button>
                            </form>
                            <div id="formMessage" class="mt-3"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Carte Google Maps et informations de contact -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body p-0">
                            <div class="ratio ratio-4x3 rounded">
                                <iframe
                                    src="https://www.google.com/maps?q=123+Avenue+de+la+Formation,+Brazzaville&output=embed"
                                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"
                                    title="Localisation Miko Formation à Brazzaville"
                                    itemprop="hasMap"></iframe>
                            </div>
                            <!-- <div class="p-4">
                                <h3 class="h5 mb-3" itemprop="name">Miko Formation</h3>
                                
                                <div class="mb-3" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                                    <h4 class="h6"><i class="fas fa-map-marker-alt me-2"></i>Adresse</h4>
                                    <p class="mb-0" itemprop="streetAddress">123 Avenue de la Formation</p>
                                    <p itemprop="addressLocality">Brazzaville, Congo</p>
                                </div>
                                
                                <div class="mb-3">
                                    <h4 class="h6"><i class="fas fa-clock me-2"></i>Horaires d'ouverture</h4>
                                    <p><strong>Lundi-Vendredi:</strong> 8h00 - 18h00</p>
                                    <p><strong>Samedi:</strong> 9h00 - 13h00</p>
                                    <p><strong>Dimanche:</strong> Fermé</p>
                                </div>
                                
                                <div class="mb-3">
                                    <h4 class="h6"><i class="fas fa-phone-alt me-2"></i>Téléphone</h4>
                                    <p itemprop="telephone"><a href="tel:+242068552497">+242 06 855 24 97</a></p>
                                </div>
                                
                                <div class="mb-3">
                                    <h4 class="h6"><i class="fas fa-envelope me-2"></i>Email</h4>
                                    <p itemprop="email"><a href="mailto:contact@mikoformation.cg">contact@mikoformation.cg</a></p>
                                </div>
                                
                                <div>
                                    <h4 class="h6"><i class="fas fa-share-alt me-2"></i>Réseaux sociaux</h4>
                                    <div class="d-flex gap-2">
                                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="fab fa-facebook-f"></i></a>
                                        <a href="#" class="btn btn-sm btn-outline-info"><i class="fab fa-linkedin-in"></i></a>
                                        <a href="#" class="btn btn-sm btn-outline-danger"><i class="fab fa-instagram"></i></a>
                                        <a href="#" class="btn btn-sm btn-outline-success"><i class="fab fa-whatsapp"></i></a>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contactForm = document.getElementById('contactForm');
        
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!contactForm.checkValidity()) {
                e.stopPropagation();
                contactForm.classList.add('was-validated');
                return;
            }
            
            // Simulation d'envoi réussi
            document.getElementById('formMessage').innerHTML = `
                <div class="alert alert-success alert-dismissible fade show">
                    <strong>Message envoyé !</strong> Nous vous répondrons dans les 24h.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            contactForm.reset();
            contactForm.classList.remove('was-validated');
        });
    });
</script>
@endpush