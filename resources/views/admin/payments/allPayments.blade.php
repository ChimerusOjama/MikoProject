@extends('layouts.sAdApp')

@section('title', 'Suivi des inscriptions - Tableau de Bord')
@section('page-title', 'Suivi des inscriptions – gestion des paiements')
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
              @else
                <p class="text-danger ms-2 mb-0 font-weight-medium">{{ $paiementEvolution }}%</p>
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
              @else
                <p class="text-danger ms-2 mb-0 font-weight-medium">{{ $reussiteEvolution }}%</p>
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

<!-- Correction des graphiques -->
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
        
        <!-- Filtres et outils de recherche améliorés -->
        <div class="row mb-4">
          <div class="col-md-8">
            <div class="d-flex">
              <div class="me-3">
                <select class="form-select" id="filter-status">
                  <option value="">Tous les statuts</option>
                  <option value="Payé">Payé</option>
                  <option value="En attente">En attente</option>
                  <option value="Rejeté">Rejeté</option>
                </select>
              </div>
              <div class="me-3">
                <select class="form-select" id="filter-formation">
                  <option value="">Toutes les formations</option>
                  @foreach($formations as $formation)
                    <option value="{{ $formation }}">{{ $formation }}</option>
                  @endforeach
                </select>
              </div>
              <div class="me-3">
                <input type="date" class="form-control" id="filter-start-date" placeholder="Date début">
              </div>
              <div class="me-3">
                <input type="date" class="form-control" id="filter-end-date" placeholder="Date fin">
              </div>
              <div>
                <input type="text" class="form-control" placeholder="Rechercher..." id="search-input">
              </div>
            </div>
          </div>
          <div class="col-md-4 text-md-end">
            <button class="btn btn-outline-secondary me-2">
              <i class="mdi mdi-export me-1"></i> Exporter
            </button>
            <button class="btn btn-outline-secondary">
              <i class="mdi mdi-printer me-1"></i> Imprimer
            </button>
          </div>
        </div>
        
        <!-- Tableau des paiements dynamique -->
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>N°</th>
                <th>Client</th>
                <th>Formation</th>
                <th>Montant</th>
                <th>Date de paiement</th>
                <th>Mode de paiement</th>
                <th>Dernière mise à jour</th>
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
                  <td>{{ $paiement->formatted_date_paiement }}</td>
                  <td>{{ $paiement->mode }}</td>
                  <td>{{ $paiement->updated_at->format('d/m/Y H:i') }}</td>
                  <td>
                    @if($paiement->statut == 'Payé')
                      <span class="badge badge-outline-success">Payé</span>
                    @elseif($paiement->statut == 'En attente')
                      <span class="badge badge-outline-warning">En attente</span>
                    @else
                      <span class="badge badge-outline-danger">Rejeté</span>
                    @endif
                  </td>
                  <td>
                    <div class="btn-group" role="group">
                      <button class="btn btn-sm btn-info btn-icon view-payment" 
                              data-id="{{ $paiement->id }}" title="Détails paiement">
                        <i class="mdi mdi-eye"></i>
                      </button>
                      <button class="btn btn-sm btn-warning btn-icon edit-payment" 
                              data-id="{{ $paiement->id }}" title="Modifier">
                        <i class="mdi mdi-pencil"></i>
                      </button>
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
        
        <!-- Pagination fonctionnelle -->
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
        <h5 class="modal-title">Détails du paiement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="payment-details-content">
        <!-- Contenu chargé dynamiquement via AJAX -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        <button type="button" class="btn btn-primary" id="edit-payment-btn">Modifier</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal d'édition -->
<div class="modal fade" id="editPaymentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modifier le paiement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="edit-payment-content">
        <!-- Formulaire chargé dynamiquement -->
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
  
  .progress {
    height: 8px;
    margin-top: 5px;
  }
    /* Conteneurs de graphiques */
  .chart-container {
    position: relative;
    height: 250px;
    width: 100%;
  }

  /* Correction responsive pour les filtres */
  @media (max-width: 992px) {
    .d-flex {
      flex-wrap: wrap;
    }
    .me-3 {
      margin-bottom: 10px;
      width: 100%;
    }
    #search-input {
      width: 100%;
    }
  }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des graphiques avec conteneurs dédiés
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
              tension: 0.4
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: true,
                position: 'top'
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
              ]
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
                  padding: 15
                }
              },
              tooltip: {
                callbacks: {
                  label: function(context) {
                    const label = context.label || '';
                    const value = context.raw || 0;
                    const total = context.chart.getDatasetMeta(0).total;
                    const percentage = Math.round((value / total) * 100);
                    return `${label}: ${value} (${percentage}%)`;
                  }
                }
              }
            }
          }
        });
      }
    };

    // Initialiser les graphiques
    initCharts();
    
    // Gestion des modals
    $('.view-payment').click(function() {
      const paymentId = $(this).data('id');
      $.get(`/admin/payments/${paymentId}/details`, function(data) {
        $('#payment-details-content').html(data);
        $('#edit-payment-btn').data('id', paymentId);
        const modal = new bootstrap.Modal(document.getElementById('paymentDetailsModal'));
        modal.show();
      });
    });
    
    $('#edit-payment-btn').click(function() {
      const paymentId = $(this).data('id');
      $.get(`/admin/payments/${paymentId}/edit`, function(data) {
        $('#edit-payment-content').html(data);
        $('#paymentDetailsModal').modal('hide');
        const editModal = new bootstrap.Modal(document.getElementById('editPaymentModal'));
        editModal.show();
      });
    });
    
    $('.edit-payment').click(function() {
      const paymentId = $(this).data('id');
      $.get(`/admin/payments/${paymentId}/edit`, function(data) {
        $('#edit-payment-content').html(data);
        const editModal = new bootstrap.Modal(document.getElementById('editPaymentModal'));
        editModal.show();
      });
    });
    
    // Filtrage des paiements
    const filterStatus = document.getElementById('filter-status');
    const filterFormation = document.getElementById('filter-formation');
    const filterStartDate = document.getElementById('filter-start-date');
    const filterEndDate = document.getElementById('filter-end-date');
    const searchInput = document.getElementById('search-input');
    
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
    
    [filterStatus, filterFormation, filterStartDate, filterEndDate].forEach(el => {
      el.addEventListener('change', applyFilters);
    });
    
    searchInput.addEventListener('input', debounce(applyFilters, 300));
    
    function debounce(func, wait) {
      let timeout;
      return function() {
        const context = this, args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), wait);
      };
    }

    // Redimensionnement des graphiques lors du redimensionnement de la fenêtre
    window.addEventListener('resize', function() {
      // Détruire et recréer les graphiques
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