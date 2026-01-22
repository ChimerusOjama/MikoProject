@extends('layouts.sAdApp')

@section('title', 'Nouvelle inscription - Tableau de Bord')
@section('page-title', 'Inscription - nouvelle inscription')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.inscriptions') }}">Inscriptions</a></li>
  <li class="breadcrumb-item active" aria-current="page">Nouvelle inscription</li>
@endsection

@section('inscription', 'active')

@section('content')
@include('components.alert-adModal')

<div class="col-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title mb-4">Formulaire d'inscription manuelle</h4>
      
      <form class="forms-sample" action="{{ route('storeInsc') }}" method="POST" id="inscriptionForm">
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
                <option value="{{ $formation->titre }}" 
                        data-prix="{{ $formation->prix }}"
                        {{ old('choixForm') == $formation->titre ? 'selected' : '' }}>
                  {{ $formation->titre }}
                </option>
                @endforeach
              </select>
            </div>
            
            <div class="form-group">
              <label for="message">Message</label>
              <textarea class="form-control" id="message" name="message" rows="2">{{ old('message') }}</textarea>
            </div>
          </div>
        </div>
        
        <div class="row mb-4">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title text-primary mb-3">
                  <i class="mdi mdi-cash-multiple me-1"></i> 
                  Paiement initial (optionnel)
                </h5>
                
                <div class="alert alert-info mb-3">
                  <small>
                    <i class="mdi mdi-information"></i>
                    Vous pouvez enregistrer un paiement initial (account) dès maintenant.
                    <strong>Minimum : 5 000 FCFA</strong>
                  </small>
                </div>
                
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="montant">Montant payé (FCFA)</label>
                      <input type="number" class="form-control" id="montant" name="montant" 
                            min="5000" step="1000" placeholder="Ex: 150000"
                            value="{{ old('montant') }}">
                      <small class="text-muted">Laisser vide pour créer sans paiement initial</small>
                    </div>
                  </div>
                  
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="account_mode">Mode de paiement</label>
                      <select class="form-select" id="account_mode" name="account_mode">
                        <option value="">Sélectionnez un mode</option>
                        @foreach(App\Models\Paiement::MODES as $value => $label)
                          <option value="{{ $value }}" {{ old('account_mode') == $value ? 'selected' : '' }}>
                            {{ $label }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="account_reference">Référence du paiement</label>
                      <input type="text" class="form-control" id="account_reference" name="account_reference" 
                            placeholder="Référence de transaction" 
                            value="{{ old('account_reference', 'ACC-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)))) }}">
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="formationPrix">Prix total de la formation</label>
                      <input type="text" class="form-control" id="formationPrix" readonly
                            placeholder="Sélectionnez d'abord une formation">
                    </div>
                  </div>
                  
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="remainingAfterPayment">Reste à payer après ce paiement</label>
                      <input type="text" class="form-control" id="remainingAfterPayment" readonly
                            placeholder="Calculé automatiquement">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="d-flex justify-content-between mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="mdi mdi-account-plus me-1"></i> Enregistrer l'inscription
          </button>
          <a href="{{ route('admin.inscriptions') }}" class="btn btn-dark">
            <i class="mdi mdi-format-list-bulleted me-1"></i> Liste des inscriptions
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  .alert-info {
    background-color: #e3f2fd;
    border-color: #bbdefb;
    color: #1565c0;
  }
  
  .form-control[readonly] {
    background-color: #f8f9fa;
    opacity: 1;
  }
  
  .card {
    border: 1px solid #e0e0e0;
  }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const formationSelect = document.getElementById('choixForm');
  const montantInput = document.getElementById('montant');
  const accountModeSelect = document.getElementById('account_mode');
  const formationPrixInput = document.getElementById('formationPrix');
  const remainingAfterPaymentInput = document.getElementById('remainingAfterPayment');
  const inscriptionForm = document.getElementById('inscriptionForm');
  
  function updateCalculations() {
    const selectedOption = formationSelect.options[formationSelect.selectedIndex];
    const formationPrix = selectedOption ? parseFloat(selectedOption.getAttribute('data-prix')) || 0 : 0;
    const montantPaye = parseFloat(montantInput.value) || 0;
    
    // Mettre à jour le prix de la formation
    if (formationPrix > 0) {
      formationPrixInput.value = formationPrix.toLocaleString() + ' FCFA';
    } else {
      formationPrixInput.value = '';
    }
    
    // Calculer le reste à payer
    const resteAPayer = formationPrix - montantPaye;
    if (formationPrix > 0) {
      if (resteAPayer >= 0) {
        remainingAfterPaymentInput.value = resteAPayer.toLocaleString() + ' FCFA';
      } else {
        remainingAfterPaymentInput.value = '0 FCFA';
      }
    } else {
      remainingAfterPaymentInput.value = '';
    }
    
    // Validation du montant
    if (montantPaye > 0) {
      // Vérifier le montant minimum
      if (montantPaye < 5000) {
        montantInput.setCustomValidity('Le montant minimum pour un paiement initial est de 5 000 FCFA');
        return;
      }
      
      // Vérifier que le montant ne dépasse pas le prix de la formation
      if (montantPaye > formationPrix) {
        montantInput.setCustomValidity(`Le montant ne peut pas dépasser le prix de la formation (${formationPrix.toLocaleString()} FCFA)`);
        return;
      }
      
      // Si un montant est saisi, le mode de paiement devient requis
      accountModeSelect.required = true;
    } else {
      // Si pas de montant, le mode de paiement n'est pas requis
      accountModeSelect.required = false;
      montantInput.setCustomValidity('');
    }
    
    // Réinitialiser si tout est bon
    montantInput.setCustomValidity('');
  }
  
  // Événements
  formationSelect.addEventListener('change', updateCalculations);
  montantInput.addEventListener('input', updateCalculations);
  
  // Validation avant soumission
  inscriptionForm.addEventListener('submit', function(e) {
    const selectedOption = formationSelect.options[formationSelect.selectedIndex];
    const formationPrix = selectedOption ? parseFloat(selectedOption.getAttribute('data-prix')) || 0 : 0;
    const montantPaye = parseFloat(montantInput.value) || 0;
    
    // Si un montant est saisi
    if (montantPaye > 0) {
      // Vérifier qu'une formation est sélectionnée
      if (formationPrix === 0) {
        e.preventDefault();
        alert('Veuillez sélectionner une formation valide');
        return false;
      }
      
      // Vérifier le minimum
      if (montantPaye < 5000) {
        e.preventDefault();
        alert('Le montant minimum pour un paiement initial est de 5 000 FCFA');
        return false;
      }
      
      // Vérifier ne dépasse pas le prix
      if (montantPaye > formationPrix) {
        e.preventDefault();
        alert(`Le montant ne peut pas dépasser le prix de la formation (${formationPrix.toLocaleString()} FCFA)`);
        return false;
      }
      
      // Vérifier le mode de paiement
      if (!accountModeSelect.value) {
        e.preventDefault();
        alert('Veuillez sélectionner un mode de paiement');
        return false;
      }
    }
    
    return true;
  });
  
  // Initialiser si des valeurs existent
  if (formationSelect.value) {
    updateCalculations();
  }
});
</script>
@endpush