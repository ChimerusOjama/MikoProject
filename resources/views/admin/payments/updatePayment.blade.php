@extends('layouts.sAdApp')

@section('title', 'Mise à jour paiement - Tableau de Bord')
@section('page-title', 'Paiements - mise à jour paiement')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('allPayments') }}">Paiements</a></li>
  <li class="breadcrumb-item active" aria-current="page">Mise à jour paiement</li>
@endsection

@section('paiement', 'active')

@section('content')
@include('components.alert-adModal')

<div class="col-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title mb-4">Formulaire de mise à jour de paiement</h4>
      
      <form class="forms-sample" action="{{ route('updatePayment', ['id' => $paiement->id]) }}" method="POST" id="paymentForm">
        @csrf
        @method('PUT')
        
        <div class="row mb-4">
          <div class="col-md-12">
            <h5 class="mb-3 text-primary"><i class="mdi mdi-information-outline me-1"></i> Informations sur l'inscription</h5>
            
            <div class="alert alert-info">
              <i class="mdi mdi-alert-circle-outline me-2"></i>
              Ce paiement ne peut être modifié qu'une seule fois. Toute modification ultérieure sera bloquée.
            </div>
            
            <div class="alert alert-warning">
              <i class="mdi mdi-clock-alert me-2"></i>
              Date du premier paiement : {{ $paiement->initial_created_at->format('d/m/Y H:i') }}
            </div>
            
            <div class="card mt-3">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="clientName">Nom complet</label>
                      <input type="text" class="form-control" id="clientName" 
                             value="{{ $paiement->inscription->name ?? 'N/A' }}" readonly>
                    </div>
                    
                    <div class="form-group">
                      <label for="clientEmail">Email</label>
                      <input type="text" class="form-control" id="clientEmail" 
                             value="{{ $paiement->inscription->email ?? 'N/A' }}" readonly>
                    </div>
                    
                    <div class="form-group">
                      <label for="clientFormation">Formation</label>
                      <input type="text" class="form-control" id="clientFormation" 
                             value="{{ $paiement->inscription->choixForm ?? 'N/A' }}" readonly>
                    </div>
                  </div>
                  
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="totalAmount">Montant total</label>
                      <input type="text" class="form-control" id="totalAmount" 
                             value="{{ number_format($totalFormation, 0, ',', ' ') }} FCFA" readonly>
                    </div>
                    
                    <div class="form-group">
                      <label for="paidAmount">Déjà payé (hors ce paiement)</label>
                      <input type="text" class="form-control" id="paidAmount" 
                             value="{{ number_format($paidWithoutCurrent, 0, ',', ' ') }} FCFA" readonly>
                    </div>
                    
                    <div class="form-group">
                      <label for="remainingAmount">Reste à payer</label>
                      <input type="text" class="form-control" id="remainingAmount" 
                             value="{{ number_format($remaining, 0, ',', ' ') }} FCFA" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="row mb-4">
          <div class="col-md-6">
            <h5 class="mb-3 text-primary"><i class="mdi mdi-cash-multiple me-1"></i> Détails du paiement actuel</h5>
            
            <div class="card bg-light">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Montant initial</label>
                      <input type="text" class="form-control" 
                             value="{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA" readonly>
                    </div>
                    
                    <div class="form-group">
                      <label>Date initiale</label>
                      <input type="text" class="form-control" 
                             value="{{ $paiement->formatted_date_paiement }}" readonly>
                    </div>
                  </div>
                  
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Statut initial</label>
                      <input type="text" class="form-control" 
                             value="{{ ucfirst($paiement->statut) }}" readonly>
                    </div>
                    
                    <div class="form-group">
                      <label>Mode initial</label>
                      <input type="text" class="form-control" 
                             value="{{ ucfirst($paiement->mode) }}" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-md-6">
            <h5 class="mb-3 text-primary"><i class="mdi mdi-update me-1"></i> Mise à jour du paiement</h5>
            
            <div class="form-group">
              <label for="paymentAmount">Nouveau montant (FCFA) <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="paymentAmount" name="amount" required 
                    min="0" step="1000" placeholder="Ex: 50000"
                    value="{{ $paiement->montant }}">
              <small class="text-muted">Montant après mise à jour</small>
            </div>
            
            <div class="form-group">
              <label for="paymentStatus">Nouveau statut <span class="text-danger">*</span></label>
              <select class="form-select" id="paymentStatus" name="statut" required>
                <option value="partiel" {{ $paiement->statut == 'partiel' ? 'selected' : '' }}>Partiel</option>
                <option value="complet" {{ $paiement->statut == 'complet' ? 'selected' : '' }}>Complet</option>
                <option value="en_attente" {{ $paiement->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="annulé" {{ $paiement->statut == 'annulé' ? 'selected' : '' }}>Annulé</option>
              </select>
              <small class="text-muted">Sélectionnez le nouveau statut</small>
            </div>
            
            <div class="form-group">
              <label for="paymentDate">Nouvelle date <span class="text-danger">*</span></label>
              <input type="date" class="form-control" id="paymentDate" name="date_paiement" required 
                    value="{{ $paiement->date_paiement->format('Y-m-d') }}">
            </div>
            
            <div class="form-group">
              <label for="paymentMethod">Nouveau mode de paiement <span class="text-danger">*</span></label>
              <select class="form-select" id="paymentMethod" name="mode" required>
                <option value="mobile" {{ $paiement->mode == 'mobile' ? 'selected' : '' }}>Mobile Money</option>
                <option value="carte" {{ $paiement->mode == 'carte' ? 'selected' : '' }}>Carte bancaire</option>
                <option value="virement" {{ $paiement->mode == 'virement' ? 'selected' : '' }}>Virement bancaire</option>
                <option value="espèce" {{ $paiement->mode == 'espèce' ? 'selected' : '' }}>Espèces</option>
                <option value="cheque" {{ $paiement->mode == 'cheque' ? 'selected' : '' }}>Chèque</option>
                <option value="autre" {{ $paiement->mode == 'autre' ? 'selected' : '' }}>Autre</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="paymentReference">Référence du paiement <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="paymentReference" name="reference" 
                    placeholder="Référence de transaction" value="{{ $paiement->reference }}" required>
              <small class="text-muted">Numéro de transaction ou référence unique</small>
            </div>
          </div>
        </div>
        
        <!-- Champs cachés -->
        <input type="hidden" name="inscription_id" value="{{ $paiement->inscription_id }}">
        <input type="hidden" name="user_email" value="{{ $paiement->inscription->email ?? '' }}">
        <input type="hidden" name="numeric_remaining" value="{{ $remaining }}">
        
        <div class="d-flex justify-content-between mt-4">
          <button type="submit" class="btn btn-warning">
            <i class="mdi mdi-update me-1"></i> Mettre à jour le paiement
          </button>
          <a href="{{ route('allPayments') }}" class="btn btn-dark">
            <i class="mdi mdi-format-list-bulleted me-1"></i> Liste des paiements
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  .card {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  }
  
  .alert-info {
    background-color: #e1f0fa;
    border-color: #b8dff0;
    color: #31708f;
  }
  
  .alert-warning {
    background-color: #fcf8e3;
    border-color: #faf2cc;
    color: #8a6d3b;
  }
  
  .form-control[readonly] {
    background-color: #f8f9fa;
    opacity: 1;
    border: 1px solid #e9ecef;
  }
  
  .bg-light {
    background-color: #f8f9fa !important;
  }
</style>
@endpush

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const paymentAmount = document.getElementById('paymentAmount');
    const paymentStatus = document.getElementById('paymentStatus');
    const paymentForm = document.getElementById('paymentForm');
    const remaining = parseFloat("{{ $remaining }}");
    const initialAmount = parseFloat("{{ $paiement->montant }}");

    // Gestion du changement de statut
    paymentStatus.addEventListener('change', function() {
      if (this.value === 'annulé') {
        paymentAmount.value = 0;
        paymentAmount.disabled = true;
      } else if (this.value === 'complet') {
        paymentAmount.value = remaining + initialAmount;
        paymentAmount.disabled = false;
      } else {
        paymentAmount.disabled = false;
        if (parseFloat(paymentAmount.value) === 0) {
          paymentAmount.value = initialAmount;
        }
      }
    });

    // Validation du montant à payer
    paymentAmount.addEventListener('input', function() {
      const enteredAmount = parseFloat(this.value) || 0;
      const maxAllowed = remaining + initialAmount;
      
      if (enteredAmount > maxAllowed && paymentStatus.value !== 'annulé') {
        alert('Le montant saisi dépasse le reste à payer de ' + maxAllowed.toLocaleString() + ' FCFA');
        this.value = maxAllowed;
      }
      
      // Mettre à jour le statut automatiquement
      if (enteredAmount === maxAllowed && enteredAmount > 0) {
        paymentStatus.value = 'complet';
      } else if (enteredAmount > 0 && enteredAmount < maxAllowed) {
        paymentStatus.value = 'partiel';
      }
    });
    
    // Validation avant soumission
    paymentForm.addEventListener('submit', function(e) {
      const paymentStatusValue = document.getElementById('paymentStatus').value;
      const paymentAmountValue = parseFloat(document.getElementById('paymentAmount').value) || 0;
      
      if (paymentStatusValue === 'annulé' && paymentAmountValue !== 0) {
        e.preventDefault();
        alert('Pour un paiement annulé, le montant doit être 0');
        return false;
      }
      
      // Vérifier si le paiement a déjà été modifié
      const initialCreatedAt = "{{ $paiement->initial_created_at }}";
      const updatedAt = "{{ $paiement->updated_at }}";
      
      if (initialCreatedAt !== updatedAt) {
        e.preventDefault();
        alert('Ce paiement a déjà été modifié une fois. Les modifications ultérieures ne sont pas autorisées.');
        return false;
      }
      
      return true;
    });

    // Validation de la date
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('paymentDate').max = today;
  });
</script>
@endpush