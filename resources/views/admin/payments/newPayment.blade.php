@extends('layouts.sAdApp')

@section('title', 'Nouveau paiement - Tableau de Bord')
@section('page-title', 'Paiements - nouveau paiement')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('allPayments') }}">Paiements</a></li>
  <li class="breadcrumb-item active" aria-current="page">Nouveau paiement</li>
@endsection

@section('paiement', 'active')

@section('content')
@include('components.alert-adModal')

<div class="col-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title mb-4">Formulaire d'enregistrement de paiement</h4>
      
      <form class="forms-sample" action="{{ route('storePayment') }}" method="POST">
        @csrf
        
        <div class="row mb-4">
          <div class="col-md-12">
            <h5 class="mb-3 text-primary"><i class="mdi mdi-magnify me-1"></i> Rechercher l'inscription</h5>
            
            <div class="form-group">
              <label for="searchClient">Rechercher par nom ou formation</label>
              <div class="input-group">
                <input type="text" class="form-control" id="searchClient" 
                      placeholder="Saisissez un nom ou une formation...">
                <button class="btn btn-outline-secondary" type="button" id="searchButton">
                  <i class="mdi mdi-magnify"></i> Rechercher
                </button>
              </div>
            </div>
            
            <div class="card mt-3" id="searchResults" style="display: none;">
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-hover mb-0">
                    <thead class="bg-light">
                      <tr>
                        <th>Sélection</th>
                        <th>Nom</th>
                        <th>Formation</th>
                        <th>Montant total</th>
                        <th>Déjà payé</th>
                        <th>Reste</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="inscription_id" id="insc1" value="1">
                          </div>
                        </td>
                        <td>Marie Dupont</td>
                        <td>Marketing Digital</td>
                        <td>150 000 FCFA</td>
                        <td>50 000 FCFA</td>
                        <td>100 000 FCFA</td>
                      </tr>
                      <tr>
                        <td>
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="inscription_id" id="insc2" value="2">
                          </div>
                        </td>
                        <td>Jean Martin</td>
                        <td>Développement Web</td>
                        <td>200 000 FCFA</td>
                        <td>100 000 FCFA</td>
                        <td>100 000 FCFA</td>
                      </tr>
                      <tr>
                        <td>
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="inscription_id" id="insc3" value="3">
                          </div>
                        </td>
                        <td>Thomas Bernard</td>
                        <td>Marketing Digital</td>
                        <td>150 000 FCFA</td>
                        <td>0 FCFA</td>
                        <td>150 000 FCFA</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="row mb-4">
          <div class="col-md-6">
            <h5 class="mb-3 text-primary"><i class="mdi mdi-account-outline me-1"></i> Informations client</h5>
            
            <div class="form-group">
              <label for="clientName">Nom complet</label>
              <input type="text" class="form-control" id="clientName" readonly>
            </div>
            
            <div class="form-group">
              <label for="clientFormation">Formation</label>
              <input type="text" class="form-control" id="clientFormation" readonly>
            </div>
            
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="totalAmount">Montant total</label>
                  <input type="text" class="form-control" id="totalAmount" readonly>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="paidAmount">Déjà payé</label>
                  <input type="text" class="form-control" id="paidAmount" readonly>
                </div>
              </div>
            </div>
            
            <div class="form-group">
              <label for="remainingAmount">Reste à payer</label>
              <input type="text" class="form-control" id="remainingAmount" readonly>
            </div>
          </div>
          
          <div class="col-md-6">
            <h5 class="mb-3 text-primary"><i class="mdi mdi-cash-multiple me-1"></i> Détails du paiement</h5>
            
            <div class="form-group">
              <label for="paymentAmount">Montant à payer (FCFA) <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="paymentAmount" name="amount" required 
                    min="1" step="1000" placeholder="Ex: 50000">
            </div>
            
            <div class="form-group">
              <label for="paymentDate">Date du paiement <span class="text-danger">*</span></label>
              <input type="date" class="form-control" id="paymentDate" name="payment_date" required 
                    value="{{ date('Y-m-d') }}">
            </div>
            
            <div class="form-group">
              <label for="paymentMethod">Mode de paiement <span class="text-danger">*</span></label>
              <select class="form-select" id="paymentMethod" name="payment_method" required>
                <option value="">Sélectionnez un mode</option>
                <option value="mobile_money">Mobile Money</option>
                <option value="carte_bancaire">Carte bancaire</option>
                <option value="virement">Virement bancaire</option>
                <option value="especes">Espèces</option>
                <option value="cheque">Chèque</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="paymentReference">Référence du paiement</label>
              <input type="text" class="form-control" id="paymentReference" name="reference" 
                    placeholder="Référence de transaction">
            </div>
          </div>
        </div>
        
        <div class="row mb-4">
          <div class="col-12">
            <h5 class="mb-3 text-primary"><i class="mdi mdi-note-text-outline me-1"></i> Notes additionnelles</h5>
            
            <div class="form-group">
              <label for="paymentNotes">Notes</label>
              <textarea class="form-control" id="paymentNotes" name="notes" 
                        placeholder="Notes supplémentaires sur le paiement" rows="4"></textarea>
            </div>
          </div>
        </div>
        
        <div class="d-flex justify-content-between mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="mdi mdi-content-save me-1"></i> Enregistrer le paiement
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
  #searchResults {
    max-height: 300px;
    overflow-y: auto;
  }
  
  .card-body.p-0 table {
    margin-bottom: 0;
  }
  
  .card-body.p-0 thead {
    position: sticky;
    top: 0;
    background: white;
    z-index: 10;
  }
  
  .form-control[readonly] {
    background-color: #f8f9fa;
    opacity: 1;
  }
  
  .input-group .btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
  }
  
  .table-hover tbody tr:hover {
    background-color: rgba(41, 98, 255, 0.05);
  }
</style>
@endpush

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Recherche d'inscription
    const searchButton = document.getElementById('searchButton');
    const searchResults = document.getElementById('searchResults');
    
    searchButton.addEventListener('click', function() {
      searchResults.style.display = 'block';
    });
    
    // Sélection d'une inscription
    const inscriptionRadios = document.querySelectorAll('input[name="inscription_id"]');
    const clientName = document.getElementById('clientName');
    const clientFormation = document.getElementById('clientFormation');
    const totalAmount = document.getElementById('totalAmount');
    const paidAmount = document.getElementById('paidAmount');
    const remainingAmount = document.getElementById('remainingAmount');
    const paymentAmount = document.getElementById('paymentAmount');
    
    inscriptionRadios.forEach(radio => {
      radio.addEventListener('change', function() {
        const row = this.closest('tr');
        const name = row.cells[1].textContent;
        const formation = row.cells[2].textContent;
        const total = row.cells[3].textContent;
        const paid = row.cells[4].textContent;
        const remaining = row.cells[5].textContent;
        
        clientName.value = name;
        clientFormation.value = formation;
        totalAmount.value = total;
        paidAmount.value = paid;
        remainingAmount.value = remaining;
        
        // Suggérer le montant à payer
        paymentAmount.value = remaining.replace(/\D/g, '');
      });
    });
    
    // Validation du montant à payer
    paymentAmount.addEventListener('change', function() {
      const maxAmount = parseInt(remainingAmount.value.replace(/\D/g, ''));
      const enteredAmount = parseInt(this.value) || 0;
      
      if (enteredAmount > maxAmount) {
        alert('Le montant saisi dépasse le reste à payer de ' + maxAmount.toLocaleString() + ' FCFA');
        this.value = maxAmount;
      }
    });
    
    // Validation de la date
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('paymentDate').max = today;
  });
</script>
@endpush