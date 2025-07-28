@extends('layouts.sAdApp')

@section('title', 'Nouvelle inscription - Tableau de Bord')
@section('page-title', 'Inscription - nouvelle inscription')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('allreserv') }}">Inscriptions</a></li>
  <li class="breadcrumb-item active" aria-current="page">Nouvelle inscription</li>
@endsection

@section('inscription', 'active')

@section('content')
@include('components.alert-adModal')

<div class="col-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title mb-4">Formulaire d'inscription manuelle</h4>
      
      <form class="forms-sample" action="{{ route('storeInsc') }}" method="POST">
        @csrf
        
        <div class="row mb-4">
          <div class="col-md-6">
            <h5 class="mb-3 text-primary"><i class="mdi mdi-account-outline me-1"></i> Informations personnelles</h5>
            
            <div class="form-group">
              <label for="name">Nom complet <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="name" name="name" required 
                    placeholder="Nom et prénom" value="{{ old('name') }}">
            </div>
            
            <div class="form-group">
              <label for="email">Email <span class="text-danger">*</span></label>
              <input type="email" class="form-control" id="email" name="email" required 
                    placeholder="Adresse email" value="{{ old('email') }}">
            </div>
            
            <div class="form-group">
              <label for="phone">Téléphone <span class="text-danger">*</span></label>
              <input type="tel" class="form-control" id="phone" name="phone" required 
                    placeholder="Numéro de téléphone" value="{{ old('phone') }}">
            </div>
          </div>
          
          <div class="col-md-6">
            <h5 class="mb-3 text-primary"><i class="mdi mdi-home-outline me-1"></i> Adresse & Formation</h5>
            
            <div class="form-group">
              <label for="address">Adresse</label>
              <input type="text" class="form-control" id="address" name="address" 
                    placeholder="Adresse complète" value="{{ old('address') }}">
            </div>
            
            <div class="form-group">
              <label for="choixForm">Formation choisie <span class="text-danger">*</span></label>
              <select class="form-select" id="choixForm" name="choixForm" required>
                <option value="">Sélectionnez une formation</option>
                @foreach($formations as $formation)
                <option value="{{ $formation->id }}" {{ old('choixForm') == $formation->id ? 'selected' : '' }}>
                  {{ $formation->titre }}
                </option>
                @endforeach
              </select>
            </div>
            
            <div class="form-group">
              <label for="montant">Montant payé (FCFA) <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="montant" name="montant" required 
                    min="0" step="1000" placeholder="Ex: 150000"
                    value="{{ old('montant') }}">
            </div>
          </div>
        </div>
        
        <div class="row mb-4">
          <div class="col-12">
            <h5 class="mb-3 text-primary"><i class="mdi mdi-message-text-outline me-1"></i> Message additionnel</h5>
            
            <div class="form-group">
              <label for="message">Message</label>
              <textarea class="form-control" id="message" name="message" 
                        placeholder="Message ou commentaires" rows="4">{{ old('message') }}</textarea>
            </div>
          </div>
        </div>
        
        <div class="d-flex justify-content-between mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="mdi mdi-account-plus me-1"></i> Enregistrer l'inscription
          </button>
          <a href="{{ route('allreserv') }}" class="btn btn-dark">
            <i class="mdi mdi-format-list-bulleted me-1"></i> Liste des inscriptions
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Afficher le message dans une modal
  document.querySelectorAll('.view-message').forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const message = this.getAttribute('data-message');
      document.getElementById('messageContent').textContent = message;
      const modal = new bootstrap.Modal(document.getElementById('messageModal'));
      modal.show();
    });
  });
  
  // Automatiser le montant quand une formation est sélectionnée
  document.getElementById('formation_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const prix = selectedOption.getAttribute('data-prix');
    
    if (prix) {
      document.getElementById('montant').value = prix;
    }
  });

  // Pré-remplir le montant si déjà dans old()
  document.addEventListener('DOMContentLoaded', function() {
    const formationId = "{{ old('formation_id') }}";
    if (formationId) {
      const formationSelect = document.getElementById('formation_id');
      const selectedOption = formationSelect.querySelector(`option[value="${formationId}"]`);
      
      if (selectedOption) {
        const prix = selectedOption.getAttribute('data-prix');
        if (prix && !document.getElementById('montant').value) {
          document.getElementById('montant').value = prix;
        }
      }
    }
  });
</script>
@endpush