@extends('layouts.adApp')

@section('title', 'Mon profil - Tableau de Bord')
@section('page-title', 'Mon profil utilisateur')
@section('breadcrumb')
    <li>Mon espace</li>
    <li>Profile</li>
@endsection

@section('profile', 'active')

@section('content')
  <section class="profile-section">
    <div class="profile-container">
      <div class="profile-sidebar">
        <div class="profile-card">
          <div class="profile-avatar">
            <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                alt="Photo de profil">
            <div class="edit-overlay">
              <i class="fas fa-camera"></i> Modifier
            </div>
          </div>
          
          <h2 class="profile-name">Jean Dupont</h2>
          <div class="profile-role">Étudiant en Développement Web</div>
          
          <div class="status-badge status-active">Compte actif</div>
          
          <div class="profile-stats">
            <div class="stat-item">
              <div class="stat-value">12</div>
              <div class="stat-label">Formations</div>
            </div>
            <div class="stat-item">
              <div class="stat-value">8</div>
              <div class="stat-label">Certificats</div>
            </div>
            <div class="stat-item">
              <div class="stat-value">92%</div>
              <div class="stat-label">Progression</div>
            </div>
          </div>
          
          <div class="profile-badges">
            <div class="badge-title">
              <i class="fas fa-medal"></i>
              Badges obtenus
            </div>
            <div class="badges-container">
              <div class="badge"><i class="fas fa-star"></i></div>
              <div class="badge"><i class="fas fa-trophy"></i></div>
              <div class="badge"><i class="fas fa-lightbulb"></i></div>
              <div class="badge"><i class="fas fa-rocket"></i></div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="profile-main">
        <div class="profile-tabs">
          <div class="tabs-header">
            <button class="tab-btn active" data-tab="overview">Aperçu</button>
            <button class="tab-btn" data-tab="edit">Modifier Profil</button>
            <button class="tab-btn" data-tab="settings">Paramètres</button>
            <button class="tab-btn" data-tab="password">Mot de passe</button>
          </div>
          
          <div class="tabs-content">
            <!-- Tab 1: Overview -->
            <div class="tab-pane active" id="overview">
              <h3 class="card-title"><i class="fas fa-info-circle"></i> À propos</h3>
              <p class="profile-about">Passionné par le développement web et les nouvelles technologies. Actuellement en formation pour devenir développeur full-stack.</p>
              
              <h3 class="card-title"><i class="fas fa-user-circle"></i> Détails du profil</h3>
              
              <div class="profile-details">
                <div class="detail-row">
                  <div class="detail-label">Nom complet</div>
                  <div class="detail-value">Jean Dupont</div>
                </div>
                
                <div class="detail-row">
                  <div class="detail-label">Email</div>
                  <div class="detail-value">jean.dupont@example.com</div>
                </div>
                
                <div class="detail-row">
                  <div class="detail-label">Téléphone</div>
                  <div class="detail-value">+33 6 12 34 56 78</div>
                </div>
                
                <div class="detail-row">
                  <div class="detail-label">Adresse</div>
                  <div class="detail-value">123 Avenue des Champs-Élysées, 75008 Paris</div>
                </div>
                
                <div class="detail-row">
                  <div class="detail-label">Date d'inscription</div>
                  <div class="detail-value">15 mars 2024</div>
                </div>
                
                <div class="detail-row">
                  <div class="detail-label">Dernière connexion</div>
                  <div class="detail-value">Aujourd'hui, 10:24</div>
                </div>
              </div>
            </div>
            
            <!-- Tab 2: Edit Profile -->
            <div class="tab-pane" id="edit">
              <form id="profileForm">
                <div class="form-row">
                  <div class="form-group">
                    <label for="firstName">Prénom</label>
                    <input type="text" id="firstName" class="form-control" value="Jean">
                  </div>
                  
                  <div class="form-group">
                    <label for="lastName">Nom</label>
                    <input type="text" id="lastName" class="form-control" value="Dupont">
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="email">Adresse Email</label>
                  <input type="email" id="email" class="form-control" value="jean.dupont@example.com">
                </div>
                
                <div class="form-group">
                  <label for="phone">Téléphone</label>
                  <input type="tel" id="phone" class="form-control" value="+33 6 12 34 56 78">
                </div>
                
                <div class="form-group">
                  <label for="bio">À propos de moi</label>
                  <textarea id="bio" class="form-control" rows="3">Passionné par le développement web et les nouvelles technologies. Actuellement en formation pour devenir développeur full-stack.</textarea>
                </div>
                
                <div class="form-group">
                  <label for="address">Adresse</label>
                  <textarea id="address" class="form-control" rows="2">123 Avenue des Champs-Élysées, 75008 Paris</textarea>
                </div>
                
                <div class="form-actions">
                  <button type="reset" class="btn btn-outline">Annuler</button>
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer
                  </button>
                </div>
              </form>
            </div>
            
            <!-- Tab 3: Settings -->
            <div class="tab-pane" id="settings">
              <form id="settingsForm">
                <div class="form-group">
                  <label for="language">Langue</label>
                  <select id="language" class="form-control">
                    <option value="fr" selected>Français</option>
                    <option value="en">English</option>
                    <option value="es">Español</option>
                  </select>
                </div>
                
                <div class="form-group">
                  <label>Notifications</label>
                  <div class="notifications-settings">
                    <label class="checkbox-item">
                      <input type="checkbox" id="emailNotifications" checked>
                      <span>Notifications par email</span>
                    </label>
                    
                    <label class="checkbox-item">
                      <input type="checkbox" id="pushNotifications">
                      <span>Notifications push</span>
                    </label>
                    
                    <label class="checkbox-item">
                      <input type="checkbox" id="smsNotifications">
                      <span>Notifications SMS</span>
                    </label>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="theme">Thème de l'application</label>
                  <select id="theme" class="form-control">
                    <option value="light" selected>Clair</option>
                    <option value="dark">Sombre</option>
                    <option value="system">Système</option>
                  </select>
                </div>
                
                <div class="form-group">
                  <label>Frequence des rappels</label>
                  <div class="radio-group">
                    <label class="radio-item">
                      <input type="radio" name="reminder" value="daily" checked>
                      <span>Quotidien</span>
                    </label>
                    
                    <label class="radio-item">
                      <input type="radio" name="reminder" value="weekly">
                      <span>Hebdomadaire</span>
                    </label>
                    
                    <label class="radio-item">
                      <input type="radio" name="reminder" value="none">
                      <span>Aucun</span>
                    </label>
                  </div>
                </div>
                
                <div class="form-actions">
                  <button type="reset" class="btn btn-outline">Réinitialiser</button>
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer
                  </button>
                </div>
              </form>
            </div>
            
            <!-- Tab 4: Change Password -->
            <div class="tab-pane" id="password">
              <form id="passwordForm">
                <div class="form-group">
                  <label for="currentPassword">Mot de passe actuel</label>
                  <div class="password-container">
                    <input type="password" id="currentPassword" class="form-control" placeholder="Entrez votre mot de passe actuel">
                    <button type="button" class="toggle-password" data-target="currentPassword">
                      <i class="fas fa-eye"></i>
                    </button>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="newPassword">Nouveau mot de passe</label>
                  <div class="password-container">
                    <input type="password" id="newPassword" class="form-control" placeholder="Entrez votre nouveau mot de passe">
                    <button type="button" class="toggle-password" data-target="newPassword">
                      <i class="fas fa-eye"></i>
                    </button>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="confirmPassword">Confirmer le mot de passe</label>
                  <div class="password-container">
                    <input type="password" id="confirmPassword" class="form-control" placeholder="Confirmez votre nouveau mot de passe">
                    <button type="button" class="toggle-password" data-target="confirmPassword">
                      <i class="fas fa-eye"></i>
                    </button>
                  </div>
                </div>
                
                <div class="password-strength">
                  <div class="strength-meter">
                    <div class="strength-bar"></div>
                  </div>
                  <div class="strength-text">Force du mot de passe: <span id="strengthValue">Faible</span></div>
                </div>
                
                <div class="form-actions">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sync-alt"></i> Mettre à jour le mot de passe
                  </button>
                </div>
              </form>
            </div>
          </div>
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