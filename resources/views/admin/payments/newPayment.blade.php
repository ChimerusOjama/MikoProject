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
      
      <form class="forms-sample" action="{{ route('storePayment') }}" method="POST" id="paymentForm">
        @csrf
        
        <div class="row mb-4">
          <div class="col-md-12">
            <h5 class="mb-3 text-primary"><i class="mdi mdi-magnify me-1"></i> Rechercher l'inscription</h5>
            
            <div class="form-group">
              <label for="searchClient">Rechercher par nom, email, formation ou téléphone</label>
              <div class="input-group">
                <input type="text" class="form-control" id="searchClient" 
                      placeholder="Saisissez un nom, email, formation ou téléphone...">
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
                        <th>Email</th>
                        <th>Formation</th>
                        <th>Montant total</th>
                        <th>Déjà payé</th>
                        <th>Reste</th>
                      </tr>
                    </thead>
                    <tbody id="resultsBody">
                      <!-- Les résultats seront chargés ici dynamiquement -->
                    </tbody>
                  </table>
                </div>
                <div id="loadingIndicator" style="display: none; text-align: center; padding: 10px;">
                  <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                  </div>
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
                    min="0" step="1000" placeholder="Ex: 50000">
              <small class="text-muted">Le montant peut être partiel ou 0 pour annulé</small>
            </div>
            
            <div class="form-group">
                <label for="paymentMethod">Mode de paiement <span class="text-danger">*</span></label>
                <select class="form-select" id="paymentMethod" name="mode" required>
                    <option value="">Sélectionnez un mode</option>
                    @foreach(App\Models\Paiement::MODES as $value => $label)
                        <option value="{{ $value }}" {{ old('mode') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('mode')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="paymentStatus">Statut du paiement <span class="text-danger">*</span></label>
                <select class="form-select" id="paymentStatus" name="statut" required>
                    <option value="">Sélectionnez un statut</option>
                    @foreach(App\Models\Paiement::STATUTS as $value => $label)
                        <option value="{{ $value }}" {{ old('statut') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('statut')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
              <label for="paymentDate">Date du paiement <span class="text-danger">*</span></label>
              <input type="date" class="form-control" id="paymentDate" name="date_paiement" required 
                    value="{{ date('Y-m-d') }}">
            </div>
            
            <div class="form-group">
              <label for="paymentReference">Référence du paiement <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="paymentReference" name="reference" 
                    placeholder="Référence de transaction" value="{{ 'MAN-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2))) }}" required readonly>
              <small class="text-muted">Numéro de transaction ou référence unique</small>
            </div>
          </div>
        </div>
        
        <!-- <div class="row mb-4">
          <div class="col-12">
            <h5 class="mb-3 text-primary"><i class="mdi mdi-note-text-outline me-1"></i> Notes additionnelles</h5>
            
            <div class="form-group">
              <label for="paymentNotes">Notes</label>
              <textarea class="form-control" id="paymentNotes" name="notes" 
                        placeholder="Notes supplémentaires sur le paiement" rows="4"></textarea>
            </div>
          </div>
        </div> -->
        
        <!-- Champs cachés -->
        <input type="hidden" id="inscriptionIdInput" name="inscription_id">
        <input type="hidden" id="userEmail" name="user_email">
        <input type="hidden" id="numericRemaining" name="numeric_remaining">
        
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
    // Éléments DOM
    const searchButton = document.getElementById('searchButton');
    const searchInput = document.getElementById('searchClient');
    const searchResults = document.getElementById('searchResults');
    const resultsBody = document.getElementById('resultsBody');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const paymentAmount = document.getElementById('paymentAmount');
    const paymentStatus = document.getElementById('paymentStatus');
    const paymentForm = document.getElementById('paymentForm');

    // Recherche d'inscription
    searchButton.addEventListener('click', function() {
        const query = searchInput.value.trim();
        
        if (query.length < 2) {
            alert('Veuillez saisir au moins 2 caractères');
            return;
        }

        // Afficher le chargement
        loadingIndicator.style.display = 'block';
        resultsBody.innerHTML = '';
        searchResults.style.display = 'block';

        // Requête AJAX
        fetch(`/admin/payments/search-inscriptions?query=${encodeURIComponent(query)}`)
            .then(response => {
                if (!response.ok) throw new Error('Erreur réseau: ' + response.statusText);
                return response.json();
            })
            .then(data => {
                loadingIndicator.style.display = 'none';

                if (data.length === 0) {
                    resultsBody.innerHTML = `
                        <tr>
                            <td colspan="7" class="text-center">Aucune inscription trouvée</td>
                        </tr>
                    `;
                    return;
                }

                data.forEach(inscription => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="inscription_id" 
                                    value="${inscription.id}" data-inscription='${JSON.stringify(inscription)}'>
                            </div>
                        </td>
                        <td>${inscription.name}</td>
                        <td>${inscription.email}</td>
                        <td>${inscription.formation}</td>
                        <td>${inscription.totalAmount.toLocaleString()} FCFA</td>
                        <td>${inscription.paidAmount.toLocaleString()} FCFA</td>
                        <td>${inscription.remaining.toLocaleString()} FCFA</td>
                    `;
                    resultsBody.appendChild(row);
                });

                // Gérer la sélection d'une inscription
                document.querySelectorAll('input[name="inscription_id"]').forEach(radio => {
                    radio.addEventListener('change', function() {
                        const inscription = JSON.parse(this.getAttribute('data-inscription'));
                        
                        document.getElementById('clientName').value = inscription.name;
                        document.getElementById('clientFormation').value = inscription.formation;
                        document.getElementById('totalAmount').value = inscription.totalAmount.toLocaleString() + ' FCFA';
                        document.getElementById('paidAmount').value = inscription.paidAmount.toLocaleString() + ' FCFA';
                        document.getElementById('remainingAmount').value = inscription.remaining.toLocaleString() + ' FCFA';
                        document.getElementById('numericRemaining').value = inscription.remaining;
                        document.getElementById('inscriptionIdInput').value = inscription.id;
                        
                        // Ajustement automatique du montant
                        if (paymentStatus.value === 'annulé') {
                            paymentAmount.value = 0;
                        } else {
                            paymentAmount.value = inscription.remaining;
                        }
                        
                        // Mettre à jour le statut automatiquement
                        paymentStatus.value = inscription.remaining > 0 ? 'partiel' : 'complet';
                        
                        // Stocker l'email pour l'envoi de notification
                        document.getElementById('userEmail').value = inscription.email;
                    });
                });
            })
            .catch(error => {
                console.error('Erreur:', error);
                loadingIndicator.style.display = 'none';
                resultsBody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-danger">
                            Erreur lors de la recherche: ${error.message}
                        </td>
                    </tr>
                `;
            });
    });

    // Gestion du changement de statut
    paymentStatus.addEventListener('change', function() {
        const numericRemaining = parseFloat(document.getElementById('numericRemaining').value) || 0;
        
        if (this.value === 'annulé') {
            paymentAmount.value = 0;
            paymentAmount.disabled = true;
        } else if (this.value === 'complet') {
            paymentAmount.value = numericRemaining;
            paymentAmount.disabled = false;
        } else {
            paymentAmount.disabled = false;
            if (paymentAmount.value === "0") {
                paymentAmount.value = numericRemaining;
            }
        }
    });

    // Validation du montant à payer
    paymentAmount.addEventListener('input', function() {
        const numericRemaining = parseFloat(document.getElementById('numericRemaining').value) || 0;
        const enteredAmount = parseFloat(this.value) || 0;
        
        if (enteredAmount > numericRemaining && paymentStatus.value !== 'annulé') {
            alert('Le montant saisi dépasse le reste à payer de ' + numericRemaining.toLocaleString() + ' FCFA');
            this.value = numericRemaining;
        }
        
        // Mettre à jour le statut automatiquement
        if (enteredAmount === numericRemaining && enteredAmount > 0) {
            paymentStatus.value = 'complet';
        } else if (enteredAmount > 0 && enteredAmount < numericRemaining) {
            paymentStatus.value = 'partiel';
        }
    });
    
    // Validation avant soumission
    paymentForm.addEventListener('submit', function(e) {
        const inscriptionId = document.getElementById('inscriptionIdInput').value;
        if (!inscriptionId) {
            e.preventDefault();
            alert('Veuillez sélectionner une inscription avant de soumettre');
            return false;
        }
        
        const paymentStatusValue = document.getElementById('paymentStatus').value;
        const paymentAmountValue = parseFloat(document.getElementById('paymentAmount').value) || 0;
        
        if (paymentStatusValue === 'annulé' && paymentAmountValue !== 0) {
            e.preventDefault();
            alert('Pour un paiement annulé, le montant doit être 0');
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