@extends('layouts.sAdApp')

@section('title', 'Nouvelle formation - Tableau de Bord')
@section('page-title', 'Formation - nouvelle formation')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('allForm') }}">Formations</a></li>
  <li class="breadcrumb-item active" aria-current="page">Nouvelle formation</li>
@endsection

@section('formation', 'active')

@section('content')
@include('components.alert-adModal')

<div class="col-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title mb-4">Formulaire de création de formation</h4>
      
      <form class="forms-sample" action="{{ route('storeForm') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row mb-4">
          <div class="col-md-6">
            <h5 class="mb-3 text-primary"><i class="mdi mdi-information-outline me-1"></i> Informations de base</h5>
            
            <div class="form-group">
              <label for="titre">Titre <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="titre" name="titre" required 
                    placeholder="Titre de la formation" value="{{ old('titre') }}">
            </div>
            
            <div class="form-group">
              <label for="description_courte">Description courte <span class="text-danger">*</span></label>
              <textarea class="form-control" id="description_courte" name="description_courte" required 
                        placeholder="Description résumée de la formation" rows="3">{{ old('description_courte') }}</textarea>
              <small class="form-text text-muted">Maximum 200 caractères</small>
            </div>
            
            <div class="form-group">
              <label for="description_longue">Description détaillée</label>
              <textarea class="form-control" id="description_longue" name="description_longue" 
                        placeholder="Contenu détaillé de la formation" rows="5">{{ old('description_longue') }}</textarea>
            </div>
          </div>
          
          <div class="col-md-6">
            <h5 class="mb-3 text-primary"><i class="mdi mdi-tag-outline me-1"></i> Catégorisation</h5>
            
            <div class="form-group">
              <label for="categorie">Catégorie <span class="text-danger">*</span></label>
              <select class="form-select" id="categorie" name="categorie" required>
                <option value="">Sélectionnez une catégorie</option>
                <option value="developpement" {{ old('categorie') == 'developpement' ? 'selected' : '' }}>Développement</option>
                <option value="bureautique" {{ old('categorie') == 'bureautique' ? 'selected' : '' }}>Bureautique</option>
                <option value="gestion" {{ old('categorie') == 'gestion' ? 'selected' : '' }}>Gestion</option>
                <option value="langues" {{ old('categorie') == 'langues' ? 'selected' : '' }}>Langues</option>
                <option value="marketing" {{ old('categorie') == 'marketing' ? 'selected' : '' }}>Marketing</option>
                <option value="design" {{ old('categorie') == 'design' ? 'selected' : '' }}>Design</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="niveau">Niveau <span class="text-danger">*</span></label>
              <select class="form-select" id="niveau" name="niveau" required>
                <option value="">Sélectionnez un niveau</option>
                <option value="debutant" {{ old('niveau') == 'debutant' ? 'selected' : '' }}>Débutant</option>
                <option value="intermediaire" {{ old('niveau') == 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                <option value="avance" {{ old('niveau') == 'avance' ? 'selected' : '' }}>Avancé</option>
              </select>
            </div>
            
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="prix">Prix (FCFA) <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" id="prix" name="prix" required 
                        min="0" max="1000000" step="0.01" placeholder="Ex: 150000.00"
                        value="{{ old('prix') }}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="duree_mois">Durée (mois) <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" id="duree_mois" name="duree_mois" required 
                        min="1" max="36" placeholder="Ex: 3"
                        value="{{ old('duree_mois') }}">
                </div>
              </div>
            </div>
            
            <div class="form-group">
              <label for="places_disponibles">Places disponibles</label>
              <input type="number" class="form-control" id="places_disponibles" name="places_disponibles" 
                    min="1" max="100" placeholder="Ex: 25"
                    value="{{ old('places_disponibles') }}">
            </div>
          </div>
        </div>
        
        <div class="row mb-4">
          <div class="col-md-6">
            <h5 class="mb-3 text-primary"><i class="mdi mdi-currency-usd me-1"></i> Configuration Stripe</h5>
            
            <div class="form-group">
              <label for="stripe_price_id">Stripe Price ID <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="stripe_price_id" name="stripe_price_id" required 
                    placeholder="Ex: price_12345 ou prod_12345" value="{{ old('stripe_price_id') }}">
              <small class="form-text text-muted">ID du produit/price dans Stripe (commence par price_ ou prod_)</small>
            </div>
            
            <div class="form-group">
              <label for="stripe_product_id">Stripe Product ID</label>
              <input type="text" class="form-control" id="stripe_product_id" name="stripe_product_id" 
                    placeholder="Ex: prod_12345" value="{{ old('stripe_product_id') }}">
              <small class="form-text text-muted">(Optionnel) ID du produit dans Stripe</small>
            </div>
          </div>
          
          <div class="col-md-6">
            <h5 class="mb-3 text-primary"><i class="mdi mdi-cog-outline me-1"></i> Paramètres</h5>
            
            <div class="form-group">
              <label for="status">Statut <span class="text-danger">*</span></label>
              <select class="form-select" id="status" name="status" required>
                <option value="">Sélectionnez un statut</option>
                <option value="publiee" {{ old('status') == 'publiee' ? 'selected' : '' }}>Publiée</option>
                <option value="brouillon" {{ old('status') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                <option value="archivee" {{ old('status') == 'archivee' ? 'selected' : '' }}>Archivée</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="date_debut">Date de début</label>
              <input type="date" class="form-control" id="date_debut" name="date_debut" 
                    value="{{ old('date_debut') }}">
            </div>
            
            <div class="form-group">
              <label for="date_fin">Date de fin</label>
              <input type="date" class="form-control" id="date_fin" name="date_fin" 
                    value="{{ old('date_fin') }}">
            </div>
          </div>
        </div>
        
        <div class="row mb-4">
          <div class="col-md-6">
            <h5 class="mb-3 text-primary"><i class="mdi mdi-image-outline me-1"></i> Visuel</h5>
            
            <div class="form-group">
              <label for="image">Image de présentation <span class="text-danger">*</span></label>
              <input type="file" class="form-control" id="image" name="image" required 
                    accept="image/jpeg,image/png,image/jpg">
              <small class="form-text text-muted">Formats acceptés: JPG, JPEG, PNG (max 2MB)</small>
              
              <!-- Aperçu de l'image -->
              <div id="image-preview" class="mt-3" style="display: none;">
                <img id="preview-image" src="#" alt="Aperçu de l'image" 
                     class="img-thumbnail" style="max-width: 100%; max-height: 200px;">
              </div>
            </div>
          </div>
          
          <!-- <div class="col-md-6">
            <h5 class="mb-3 text-primary"><i class="mdi mdi-file-document-outline me-1"></i> Contenu additionnel</h5>
            
            <div class="form-group">
              <label for="programme">Programme de la formation (PDF)</label>
              <input type="file" class="form-control" id="programme" name="programme" 
                    accept=".pdf">
              <small class="form-text text-muted">Télécharger le programme détaillé</small>
            </div>
            
            <div class="form-group">
              <label for="prerequis">Prérequis</label>
              <textarea class="form-control" id="prerequis" name="prerequis" 
                        placeholder="Prérequis pour suivre la formation" rows="3">{{ old('prerequis') }}</textarea>
            </div>
          </div> -->
        </div>
        
        <div class="d-flex justify-content-between mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="mdi mdi-content-save me-1"></i> Créer la formation
          </button>
          <a href="{{ route('allForm') }}" class="btn btn-dark">
            <i class="mdi mdi-format-list-bulleted me-1"></i> Liste des formations
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Aperçu de l'image de la formation
  document.getElementById('image').addEventListener('change', function(e) {
    const preview = document.getElementById('image-preview');
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
  
  // Validation de la description courte
  document.getElementById('description_courte').addEventListener('input', function() {
    if (this.value.length > 200) {
      this.value = this.value.substring(0, 200);
    }
  });
  
  // Validation des dates
  document.getElementById('date_fin').addEventListener('change', function() {
    const startDate = new Date(document.getElementById('date_debut').value);
    const endDate = new Date(this.value);
    
    if (startDate && endDate && endDate < startDate) {
      alert('La date de fin ne peut pas être antérieure à la date de début.');
      this.value = '';
    }
  });
</script>
@endpush