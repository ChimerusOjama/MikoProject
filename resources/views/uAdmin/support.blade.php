@extends('layouts.adApp')

@section('title', 'Support/Contact - Tableau de Bord')
@section('page-title', 'Support & Contact')
@section('breadcrumb')
    <li>Admin</li>
    <li>Support</li>
@endsection

@section('support', 'active')

@section('content')
  <section class="support-section">
    <div class="support-header">
      <h2 class="page-title">Support & Contact</h2>
      <p class="page-description">Notre équipe est disponible pour répondre à toutes vos questions et préoccupations.</p>
    </div>

    <div class="support-grid">
      <!-- Contact Information -->
      <div class="contact-info">
        <div class="info-grid">
          <div class="info-card">
            <div class="info-icon">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <h3>Adresse</h3>
            <p>123 Avenue des Champs-Élysées<br>75008 Paris, France</p>
          </div>
          
          <div class="info-card">
            <div class="info-icon">
              <i class="fas fa-phone-alt"></i>
            </div>
            <h3>Téléphone</h3>
            <p>+33 1 23 45 67 89<br>+33 6 12 34 56 78</p>
          </div>
          
          <div class="info-card">
            <div class="info-icon">
              <i class="fas fa-envelope"></i>
            </div>
            <h3>Email</h3>
            <p>support@mikoformation.fr<br>contact@mikoformation.fr</p>
          </div>
          
          <div class="info-card">
            <div class="info-icon">
              <i class="fas fa-clock"></i>
            </div>
            <h3>Horaires d'ouverture</h3>
            <p>Lundi - Vendredi: 9h00 - 18h00<br>Samedi: jusqu'à 16h00</p>
          </div>
        </div>
        
        <div class="support-map">
          <div class="map-placeholder">
            <i class="fas fa-map-marked-alt"></i>
            <p>Carte interactive</p>
          </div>
        </div>
      </div>
      
      <!-- Contact Form -->
      <div class="contact-form-container">
        <div class="contact-form-card">
          <h3 class="card-title"><i class="fas fa-paper-plane"></i> Envoyer un message</h3>
          
          <form id="supportForm" class="contact-form">
            <div class="form-group">
              <label for="supportName">Votre nom</label>
              <input type="text" id="supportName" class="form-control" placeholder="Entrez votre nom complet">
            </div>
            
            <div class="form-group">
              <label for="supportEmail">Votre email</label>
              <input type="email" id="supportEmail" class="form-control" placeholder="Entrez votre adresse email">
            </div>
            
            <div class="form-group">
              <label for="supportSubject">Sujet</label>
              <select id="supportSubject" class="form-control">
                <option value="" disabled selected>Sélectionnez un sujet</option>
                <option value="technical">Problème technique</option>
                <option value="billing">Question de facturation</option>
                <option value="course">Question sur une formation</option>
                <option value="account">Problème de compte</option>
                <option value="other">Autre</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="supportMessage">Message</label>
              <textarea id="supportMessage" class="form-control" rows="6" placeholder="Décrivez votre problème ou question en détail"></textarea>
            </div>
            
            <div class="form-group">
              <label for="supportFile">Pièce jointe (optionnel)</label>
              <div class="file-upload">
                <input type="file" id="supportFile" class="file-input">
                <label for="supportFile" class="file-label">
                  <i class="fas fa-cloud-upload-alt"></i> Choisir un fichier
                </label>
                <span class="file-name">Aucun fichier sélectionné</span>
              </div>
            </div>
            
            <div class="form-status">
              <div class="loading-indicator">
                <i class="fas fa-spinner fa-spin"></i> Envoi en cours...
              </div>
              <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> Veuillez corriger les erreurs dans le formulaire
              </div>
              <div class="success-message">
                <i class="fas fa-check-circle"></i> Votre message a été envoyé avec succès!
              </div>
            </div>
            
            <div class="form-actions">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Envoyer le message
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    
    <div class="faq-section">
      <h3 class="section-title"><i class="fas fa-question-circle"></i> Questions Fréquentes</h3>
      
      <div class="faq-grid">
        <div class="faq-card">
          <h4><i class="fas fa-lock"></i> Comment réinitialiser mon mot de passe?</h4>
          <p>Si vous avez oublié votre mot de passe, cliquez sur "Mot de passe oublié" sur la page de connexion. Vous recevrez un email avec des instructions pour réinitialiser votre mot de passe.</p>
        </div>
        
        <div class="faq-card">
          <h4><i class="fas fa-graduation-cap"></i> Comment accéder à mes formations?</h4>
          <p>Toutes vos formations sont disponibles dans la section "Mes Formations" de votre tableau de bord. Cliquez sur "Continuer" pour reprendre où vous vous étiez arrêté.</p>
        </div>
        
        <div class="faq-card">
          <h4><i class="fas fa-certificate"></i> Comment obtenir mon certificat?</h4>
          <p>Une fois une formation terminée avec succès, votre certificat sera disponible dans la section "Mes Formations". Vous pourrez le télécharger au format PDF.</p>
        </div>
        
        <div class="faq-card">
          <h4><i class="fas fa-credit-card"></i> Quels modes de paiement acceptez-vous?</h4>
          <p>Nous acceptons les cartes de crédit (Visa, MasterCard, American Express) ainsi que les virements bancaires. Tous les paiements sont sécurisés.</p>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
<script>
  // Scripts spécifiques au dashboard
  document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard admin chargé');
  });
</script>
@endpush