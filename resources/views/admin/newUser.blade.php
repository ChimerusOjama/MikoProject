@extends('layouts.sAdApp')

@section('title', 'Nouvel utilisateur - Tableau de Bord')
@section('page-title', 'Utilisateurs - nouvel utilisateur')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('allUsers') }}">Utilisateurs</a></li>
  <li class="breadcrumb-item active" aria-current="page">Nouvel utilisateur</li>
@endsection

@section('utilisateurs', 'active')

@section('content')
@include('components.alert-adModal')

<div class="col-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title mb-4">Formulaire de création d'utilisateur</h4>
      
      <form class="forms-sample" action="{{ route('storeUser') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row mb-4">
          <div class="col-md-6">
            <h5 class="mb-3 text-primary"><i class="mdi mdi-account-outline me-1"></i> Informations personnelles</h5>
            
            <div class="form-group">
              <label for="first_name">Prénom <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="first_name" name="first_name" required 
                     placeholder="Prénom de l'utilisateur" value="{{ old('first_name') }}">
            </div>
            
            <div class="form-group">
              <label for="last_name">Nom <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="last_name" name="last_name" required 
                     placeholder="Nom de famille" value="{{ old('last_name') }}">
            </div>
            
            <div class="form-group">
              <label for="email">Email <span class="text-danger">*</span></label>
              <input type="email" class="form-control" id="email" name="email" required 
                     placeholder="exemple@domaine.com" value="{{ old('email') }}">
            </div>
            
            <div class="form-group">
              <label for="phone">Téléphone</label>
              <input type="tel" class="form-control" id="phone" name="phone" 
                     placeholder="06 XXX XX XX" value="{{ old('phone') }}">
            </div>

            <div class="form-group">
              <label for="address">Adresse</label>
              <input type="text" class="form-control" id="address" name="address" 
                     placeholder="Adresse de l'utilisateur" value="{{ old('address') }}">
            </div>
          </div>
          
          <div class="col-md-6">
            <h5 class="mb-3 text-primary"><i class="mdi mdi-lock-outline me-1"></i> Authentification</h5>
            
            <div class="form-group">
              <label for="password">Mot de passe <span class="text-danger">*</span></label>
              <input type="password" class="form-control" id="password" name="password" required 
                     placeholder="••••••••">
              <small class="form-text text-muted">8 caractères minimum</small>
            </div>
            
            <div class="form-group">
              <label for="password_confirmation">Confirmation mot de passe <span class="text-danger">*</span></label>
              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required 
                     placeholder="••••••••">
            </div>
            
            <div class="form-group">
              <label for="usertype">Rôle <span class="text-danger">*</span></label>
              <select class="form-select" id="usertype" name="usertype" required>
                <option value="">Sélectionnez un rôle</option>
                <option value="admin" {{ old('usertype') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                <option value="formateur" {{ old('usertype') == 'formateur' ? 'selected' : '' }}>Formateur</option>
                <option value="agent" {{ old('usertype') == 'agent' ? 'selected' : '' }}>Agent d'accueil</option>
                <option value="user" {{ old('usertype') == 'user' ? 'selected' : '' }}>Apprenant</option>
              </select>
            </div>
          </div>
        </div>
        
        <!-- Section Photo de profil -->
        <div class="row mb-4">
          <div class="col-12">
            <h5 class="mb-3 text-primary"><i class="mdi mdi-camera-outline me-1"></i> Photo de profil</h5>
            
            <div class="form-group">
              <label for="profile_photo">Photo (optionnel)</label>
              <input type="file" class="form-control" id="profile_photo" name="profile_photo" 
                     accept="image/jpeg,image/png,image/jpg">
              <small class="form-text text-muted">Formats acceptés: JPG, JPEG, PNG (max 2MB)</small>
              
              <!-- Aperçu de la photo -->
              <div id="photo-preview" class="mt-3" style="display: none;">
                <img id="preview-image" src="#" alt="Aperçu de la photo" 
                     class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
              </div>
            </div>
          </div>
        </div>
        
        <!-- Section Informations supplémentaires -->
        <!-- <div class="row mb-4">
          <div class="col-md-6">
            <h5 class="mb-3 text-primary"><i class="mdi mdi-home-outline me-1"></i> Adresse</h5>
            
            <div class="form-group">
              <label for="address">Adresse</label>
              <textarea class="form-control" id="address" name="address" 
                        placeholder="Adresse complète" rows="2">{{ old('address') }}</textarea>
            </div>
            
            <div class="form-group">
              <label for="city">Ville</label>
              <input type="text" class="form-control" id="city" name="city" 
                     placeholder="Ville" value="{{ old('city') }}">
            </div>
          </div>
          
          <div class="col-md-6">
            <h5 class="mb-3 text-primary"><i class="mdi mdi-information-outline me-1"></i> Informations complémentaires</h5>
            
            <div class="form-group">
              <label for="bio">Biographie</label>
              <textarea class="form-control" id="bio" name="bio" 
                        placeholder="Quelques mots sur l'utilisateur..." rows="2">{{ old('bio') }}</textarea>
            </div>
            
            <div class="form-group">
              <label for="status">Statut du compte</label>
              <select class="form-select" id="status" name="status">
                <option value="actif" selected>Actif</option>
                <option value="inactif">Inactif</option>
                <option value="suspendu">Suspendu</option>
              </select>
            </div>
          </div>
        </div> -->
        
        <div class="d-flex justify-content-between mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="mdi mdi-account-plus me-1"></i> Créer l'utilisateur
          </button>
            <a href="{{ route('allUsers') }}" class="btn btn-dark">Liste des utilisateurs</a>

          <!-- <a href="{{ route('allUsers') }}" class="btn btn-light">
            <i class="mdi mdi-close me-1"></i> Annuler
          </a> -->
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Aperçu de la photo de profil
  document.getElementById('profile_photo').addEventListener('change', function(e) {
    const preview = document.getElementById('photo-preview');
    const previewImage = document.getElementById('preview-image');
    
    if (this.files && this.files[0]) {
      const reader = new FileReader();
      
      reader.onload = function(e) {
        previewImage.src = e.target.result;
        preview.style.display = 'block';
      }
      
      reader.readAsDataURL(this.files[0]);
    } else {
      preview.style.display = 'none';
    }
  });
  
  // Validation du mot de passe
  document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;
    
    if (password !== confirmPassword) {
      e.preventDefault();
      alert('Les mots de passe ne correspondent pas.');
    }
    
    if (password.length < 8) {
      e.preventDefault();
      alert('Le mot de passe doit contenir au moins 8 caractères.');
    }
  });
</script>
@endpush