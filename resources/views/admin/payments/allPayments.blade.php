@extends('layouts.sAdApp')

@section('title', 'Suivi des paiements - Tableau de Bord')
@section('page-title', 'Suivi des paiements')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active" aria-current="page">Gestion des paiements</li>
@endsection

@section('paiement', 'active')

@section('content')
@include('components.alert-adModal')

<div class="row">
  <!-- Cartes de résumé -->
  <div class="col-sm-4 grid-margin">
    <div class="card">
      <div class="card-body">
        <h5>Total Paiements</h5>
        <div class="row">
          <div class="col-8 col-sm-12 col-xl-8 my-auto">
            <div class="d-flex d-sm-block d-md-flex align-items-center">
              <h2 class="mb-0">{{ number_format($totalPaiements, 0, ',', ' ') }} FCFA</h2>
              @if($paiementEvolution > 0)
                <p class="text-success ms-2 mb-0 font-weight-medium">+{{ $paiementEvolution }}%</p>
              @elseif($paiementEvolution < 0)
                <p class="text-danger ms-2 mb-0 font-weight-medium">{{ $paiementEvolution }}%</p>
              @else
                <p class="text-muted ms-2 mb-0 font-weight-medium">0%</p>
              @endif
            </div>
            <h6 class="text-muted font-weight-normal">Depuis le mois dernier</h6>
          </div>
          <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
            <i class="icon-lg mdi mdi-cash-multiple text-primary ms-auto"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-sm-4 grid-margin">
    <div class="card">
      <div class="card-body">
        <h5>Paiements En Attente</h5>
        <div class="row">
          <div class="col-8 col-sm-12 col-xl-8 my-auto">
            <div class="d-flex d-sm-block d-md-flex align-items-center">
              <h2 class="mb-0">{{ $paiementsEnAttente }}</h2>
              <div class="position-relative ms-2">
                <div class="spinner-grow spinner-grow-sm text-warning" role="status"></div>
              </div>
            </div>
            <h6 class="text-muted font-weight-normal">Montant total: {{ number_format($montantEnAttente, 0, ',', ' ') }} FCFA</h6>
          </div>
          <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
            <i class="icon-lg mdi mdi-clock-alert text-warning ms-auto"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-sm-4 grid-margin">
    <div class="card">
      <div class="card-body">
        <h5>Paiements Réussis</h5>
        <div class="row">
          <div class="col-8 col-sm-12 col-xl-8 my-auto">
            <div class="d-flex d-sm-block d-md-flex align-items-center">
              <h2 class="mb-0">{{ $paiementsReussis }}</h2>
              @if($reussiteEvolution > 0)
                <p class="text-success ms-2 mb-0 font-weight-medium">+{{ $reussiteEvolution }}%</p>
              @elseif($reussiteEvolution < 0)
                <p class="text-danger ms-2 mb-0 font-weight-medium">{{ $reussiteEvolution }}%</p>
              @else
                <p class="text-muted ms-2 mb-0 font-weight-medium">0%</p>
              @endif
            </div>
            <h6 class="text-muted font-weight-normal">Taux de réussite: {{ $tauxReussite }}%</h6>
          </div>
          <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
            <i class="icon-lg mdi mdi-check-circle text-success ms-auto"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Graphiques -->
<div class="row mt-4">
  <div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Évolution des Paiements</h4>
        <div class="chart-container" style="position: relative; height:250px; width:100%">
          <canvas id="paymentTrendChart"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Répartition par Méthode</h4>
        <div class="chart-container" style="position: relative; height:250px; width:100%">
          <canvas id="paymentMethodChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 class="card-title mb-0">Suivi des paiements</h4>
          <div>
            <a href="{{ route('newPayment') }}" class="btn btn-primary addLink">
              <i class="mdi mdi-plus-circle me-1"></i> Nouveau paiement
            </a>
          </div>
        </div>
        
        <!-- Filtres et outils de recherche -->
        <div class="row mb-4">
          <div class="col-md-8">
            <div class="d-flex flex-wrap">
              <div class="me-3 mb-2" style="min-width: 150px;">
                <select class="form-select" id="filter-status">
                  <option value="">Tous les statuts</option>
                  @foreach(App\Models\Paiement::STATUTS as $value => $label)
                    <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                      {{ $label }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="me-3 mb-2" style="min-width: 200px;">
                <select class="form-select" id="filter-formation">
                  <option value="">Toutes les formations</option>
                  @foreach($formations as $formation)
                    <option value="{{ $formation }}" {{ request('formation') == $formation ? 'selected' : '' }}>
                      {{ $formation }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="me-3 mb-2" style="min-width: 150px;">
                <input type="date" class="form-control" id="filter-start-date" 
                       placeholder="Date début" value="{{ request('start_date') }}">
              </div>
              <div class="me-3 mb-2" style="min-width: 150px;">
                <input type="date" class="form-control" id="filter-end-date" 
                       placeholder="Date fin" value="{{ request('end_date') }}">
              </div>
              <div class="mb-2" style="min-width: 200px;">
                <input type="text" class="form-control" placeholder="Rechercher..." 
                       id="search-input" value="{{ request('search') }}">
              </div>
            </div>
          </div>
          <div class="col-md-4 text-md-end mt-2 mt-md-0">
            <button class="btn btn-outline-secondary me-2" id="resetFiltersBtn">
              <i class="mdi mdi-refresh me-1"></i> Réinitialiser
            </button>
          </div>
        </div>
        
        <!-- Tableau des paiements -->
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>N°</th>
                <th>Client</th>
                <th>Formation</th>
                <th>Montant</th>
                <th>Type</th>
                <th>Date de paiement</th>
                <th>Mode de paiement</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($paiements as $paiement)
                <tr>
                  <td>#{{ str_pad($paiement->id, 4, '0', STR_PAD_LEFT) }}</td>
                  <td>{{ $paiement->inscription->name ?? 'N/A' }}</td>
                  <td>{{ $paiement->inscription->choixForm ?? 'N/A' }}</td>
                  <td>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
                  <td>
                    <span class="badge badge-outline-info">
                      {{ $paiement->account_type_label }}
                    </span>
                  </td>
                  <td>{{ $paiement->date_paiement->format('d/m/Y') }}</td>
                  <td>{{ $paiement->mode_label }}</td>
                  <td>
                    @if($paiement->statut == 'complet')
                      <span class="badge badge-outline-success">Complet</span>
                    @elseif($paiement->statut == 'partiel')
                      <span class="badge badge-outline-warning">Partiel</span>
                    @elseif($paiement->statut == 'annulé')
                      <span class="badge badge-outline-danger">Annulé</span>
                    @else
                      <span class="badge badge-outline-secondary">{{ $paiement->statut }}</span>
                    @endif
                  </td>
                  <td>
                    <div class="btn-group" role="group">
                      <button class="btn btn-sm btn-info btn-icon" 
                              data-bs-toggle="modal" 
                              data-bs-target="#paymentDetailsModal"
                              data-id="{{ $paiement->id }}"
                              data-client="{{ $paiement->inscription->name ?? 'N/A' }}"
                              data-email="{{ $paiement->inscription->email ?? 'N/A' }}"
                              data-phone="{{ $paiement->inscription->phone ?? 'N/A' }}"
                              data-formation="{{ $paiement->inscription->choixForm ?? 'N/A' }}"
                              data-montant="{{ $paiement->montant }}"
                              data-date-paiement="{{ $paiement->date_paiement->format('d/m/Y') }}"
                              data-mode="{{ $paiement->mode_label }}"
                              data-statut="{{ $paiement->statut }}"
                              data-reference="{{ $paiement->reference }}"
                              data-account-type="{{ $paiement->account_type_label }}"
                              onclick="loadPaymentDetails(this)">
                        <i class="mdi mdi-eye"></i>
                      </button>
                      @if(isset($paiement->id))
                      <a href="{{ route('updatePaymentView', $paiement->id) }}" 
                         class="btn btn-sm btn-warning btn-icon" title="Modifier">
                        <i class="mdi mdi-pencil"></i>
                      </a>
                      @endif
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" class="text-center">Aucun paiement enregistré</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4">
          <div class="text-muted">
            Affichage de {{ $paiements->firstItem() }} à {{ $paiements->lastItem() }} sur {{ $paiements->total() }} paiements
          </div>
          <nav aria-label="Page navigation">
            {{ $paiements->links() }}
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Détails Paiement -->
<div class="modal fade" id="paymentDetailsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Détails du paiement #<span id="detailId"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-4">
          <div class="col-md-6">
            <h6><i class="mdi mdi-account-outline me-1"></i> Informations client</h6>
            <ul class="list-group">
              <li class="list-group-item d-flex justify-content-between">
                <span class="text-muted">Nom :</span>
                <strong id="detailClient"></strong>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span class="text-muted">Email :</span>
                <strong id="detailEmail"></strong>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span class="text-muted">Téléphone :</span>
                <strong id="detailPhone"></strong>
              </li>
            </ul>
          </div>
          <div class="col-md-6">
            <h6><i class="mdi mdi-school me-1"></i> Formation</h6>
            <ul class="list-group">
              <li class="list-group-item d-flex justify-content-between">
                <span class="text-muted">Formation :</span>
                <strong id="detailFormation"></strong>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span class="text-muted">Type de compte :</span>
                <strong id="detailAccountType"></strong>
              </li>
            </ul>
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-6">
            <h6><i class="mdi mdi-cash-multiple me-1"></i> Détails paiement</h6>
            <ul class="list-group">
              <li class="list-group-item d-flex justify-content-between">
                <span class="text-muted">Montant :</span>
                <strong id="detailMontant"></strong>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span class="text-muted">Mode :</span>
                <strong id="detailMode"></strong>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span class="text-muted">Référence :</span>
                <strong id="detailReference"></strong>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span class="text-muted">Date paiement :</span>
                <strong id="detailDatePaiement"></strong>
              </li>
            </ul>
          </div>
          <div class="col-md-6">
            <h6><i class="mdi mdi-information-outline me-1"></i> Statut</h6>
            <ul class="list-group">
              <li class="list-group-item d-flex justify-content-between">
                <span class="text-muted">Statut :</span>
                <span id="detailStatut"></span>
              </li>
            </ul>
            <div class="mt-3 p-3 bg-light rounded">
              <h6 class="text-muted mb-2">Notes</h6>
              <p class="mb-0" id="detailNotes">Aucune note supplémentaire</p>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        <button type="button" class="btn btn-primary" onclick="editPayment()" id="editPaymentBtn">
          <i class="mdi mdi-pencil me-1"></i> Modifier
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  .card {
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
  }
  
  .card:hover {
    transform: translateY(-5px);
  }
  
  .table-hover tbody tr:hover {
    background-color: rgba(41, 98, 255, 0.05);
  }
  
  .badge-outline-success {
    background-color: #d4edda;
    color: #155724;
    padding: 5px 10px;
    border-radius: 12px;
    border: 1px solid #c3e6cb;
  }
  
  .badge-outline-warning {
    background-color: #fff3cd;
    color: #856404;
    padding: 5px 10px;
    border-radius: 12px;
    border: 1px solid #ffeeba;
  }
  
  .badge-outline-danger {
    background-color: #f8d7da;
    color: #721c24;
    padding: 5px 10px;
    border-radius: 12px;
    border: 1px solid #f5c6cb;
  }
  
  .badge-outline-info {
    background-color: #d1ecf1;
    color: #0c5460;
    padding: 5px 10px;
    border-radius: 12px;
    border: 1px solid #bee5eb;
  }
  
  .badge-outline-secondary {
    background-color: #f8f9fa;
    color: #6c757d;
    padding: 5px 10px;
    border-radius: 12px;
    border: 1px solid #dee2e6;
  }
  
  .pagination .page-item.active .page-link {
    background-color: #2962ff;
    border-color: #2962ff;
  }
  
  .pagination .page-link {
    color: #2962ff;
  }
  
  .pagination .page-link:hover {
    color: #0039cb;
  }
  
  .btn-icon {
    padding: 5px 8px;
  }
  
  .chart-container {
    position: relative;
    height: 250px;
    width: 100%;
  }

  .list-group-item {
    border-left: none;
    border-right: none;
  }
  
  .list-group-item:first-child {
    border-top: none;
  }
  
  .list-group-item:last-child {
    border-bottom: none;
  }

  @media (max-width: 992px) {
    .d-flex {
      flex-wrap: wrap;
    }
    .me-3 {
      margin-bottom: 10px;
    }
  }
</style>
@endpush

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Fonction pour charger les détails dans le modal
    function loadPaymentDetails(el) {
        const paymentId = el.getAttribute('data-id');
        
        // Mettre à jour les champs avec les données du bouton
        document.getElementById('detailId').textContent = paymentId;
        document.getElementById('detailClient').textContent = el.getAttribute('data-client');
        document.getElementById('detailEmail').textContent = el.getAttribute('data-email');
        document.getElementById('detailPhone').textContent = el.getAttribute('data-phone');
        document.getElementById('detailFormation').textContent = el.getAttribute('data-formation');
        document.getElementById('detailMontant').textContent = parseInt(el.getAttribute('data-montant')).toLocaleString() + ' FCFA';
        document.getElementById('detailDatePaiement').textContent = el.getAttribute('data-date-paiement');
        document.getElementById('detailMode').textContent = el.getAttribute('data-mode');
        document.getElementById('detailReference').textContent = el.getAttribute('data-reference');
        document.getElementById('detailAccountType').textContent = el.getAttribute('data-account-type');
        
        // Mettre à jour le statut
        const statut = el.getAttribute('data-statut');
        const statutElement = document.getElementById('detailStatut');
        statutElement.innerHTML = '';
        
        let badgeClass = 'badge badge-outline-secondary';
        let statutText = statut;
        
        if (statut === 'complet') {
            badgeClass = 'badge badge-outline-success';
            statutText = 'Complet';
        } else if (statut === 'partiel') {
            badgeClass = 'badge badge-outline-warning';
            statutText = 'Partiel';
        } else if (statut === 'annulé') {
            badgeClass = 'badge badge-outline-danger';
            statutText = 'Annulé';
        }
        
        statutElement.innerHTML = `<span class="${badgeClass}">${statutText}</span>`;
        
        // Stocker l'ID pour le bouton de modification
        document.getElementById('editPaymentBtn').setAttribute('data-payment-id', paymentId);
    }

    // Fonction pour rediriger vers la page de modification
    function editPayment() {
        const paymentId = document.getElementById('editPaymentBtn').getAttribute('data-payment-id');
        if (paymentId) {
            window.location.href = `/admin/Modifier_paiement/paiement=${paymentId}`;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialisation des graphiques
        const initCharts = function() {
            // Graphique Évolution des paiements
            const trendCtx = document.getElementById('paymentTrendChart');
            if (trendCtx) {
                new Chart(trendCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($paymentTrends->pluck('month')) !!},
                        datasets: [{
                            label: 'Montant des paiements (FCFA)',
                            data: {!! json_encode($paymentTrends->pluck('total')) !!},
                            borderColor: '#2962ff',
                            backgroundColor: 'rgba(41, 98, 255, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ' + context.raw.toLocaleString('fr-FR') + ' FCFA';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString('fr-FR') + ' FCFA';
                                    }
                                }
                            }
                        }
                    }
                });
            }
            
            // Graphique Répartition par méthode
            const methodCtx = document.getElementById('paymentMethodChart');
            if (methodCtx) {
                new Chart(methodCtx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($paymentMethods->pluck('mode')) !!},
                        datasets: [{
                            data: {!! json_encode($paymentMethods->pluck('count')) !!},
                            backgroundColor: [
                                '#2962ff', '#ff6b6b', '#51cf66', '#fcc419', '#cc5de8', '#ff922b'
                            ],
                            borderWidth: 1,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    boxWidth: 15,
                                    padding: 15,
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.chart.getDatasetMeta(0).total;
                                        const percentage = Math.round((value / total) * 100);
                                        return `${label}: ${value} paiement${value > 1 ? 's' : ''} (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        cutout: '65%'
                    }
                });
            }
        };

        // Initialiser les graphiques
        initCharts();
        
        // Filtrage des paiements
        const filterStatus = document.getElementById('filter-status');
        const filterFormation = document.getElementById('filter-formation');
        const filterStartDate = document.getElementById('filter-start-date');
        const filterEndDate = document.getElementById('filter-end-date');
        const searchInput = document.getElementById('search-input');
        const resetFiltersBtn = document.getElementById('resetFiltersBtn');
        
        function applyFilters() {
            const params = new URLSearchParams({
                status: filterStatus.value,
                formation: filterFormation.value,
                start_date: filterStartDate.value,
                end_date: filterEndDate.value,
                search: searchInput.value
            });
            
            window.location.href = `{{ route('allPayments') }}?${params.toString()}`;
        }
        
        // Appliquer les filtres
        [filterStatus, filterFormation, filterStartDate, filterEndDate].forEach(el => {
            el.addEventListener('change', applyFilters);
        });
        
        // Fonction debounce pour la recherche
        function debounce(func, wait) {
            let timeout;
            return function() {
                const context = this, args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        }
        
        searchInput.addEventListener('input', debounce(applyFilters, 500));
        
        // Réinitialiser les filtres
        resetFiltersBtn.addEventListener('click', function() {
            window.location.href = '{{ route("allPayments") }}';
        });
        
        // Validation des dates
        const today = new Date().toISOString().split('T')[0];
        if (filterStartDate) {
            filterStartDate.max = today;
        }
        if (filterEndDate) {
            filterEndDate.max = today;
        }
        
        // Si une date de début est définie, limiter la date de fin
        if (filterStartDate && filterEndDate) {
            filterStartDate.addEventListener('change', function() {
                filterEndDate.min = this.value;
            });
            
            filterEndDate.addEventListener('change', function() {
                filterStartDate.max = this.value;
            });
        }
        
        // Redimensionnement des graphiques
        window.addEventListener('resize', function() {
            if (typeof Chart !== 'undefined') {
                Chart.helpers.each(Chart.instances, function(instance) {
                    instance.destroy();
                });
                initCharts();
            }
        });
    });
  </script>
@endpush