@extends('layouts.sAdApp')

@section('title', 'Suivi des inscriptions - Tableau de Bord')
@section('page-title', 'Suivi des inscriptions – gestion des paiements')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
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
        <h5>Total formations</h5>
        <div class="row">
          <div class="col-8 col-sm-12 col-xl-8 my-auto">
            <div class="d-flex d-sm-block d-md-flex align-items-center">
              <h2 class="mb-0">5</h2>
              <p class="text-success ms-2 mb-0 font-weight-medium">+3,5%</p>
            </div>
            <h6 class="text-muted font-weight-normal">+11,38% depuis le mois dernier</h6>
          </div>
          <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
            <i class="icon-lg mdi mdi-school text-primary ms-auto"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-4 grid-margin">
    <div class="card">
      <div class="card-body">
        <h5>Total inscrits</h5>
        <div class="row">
          <div class="col-8 col-sm-12 col-xl-8 my-auto">
            <div class="d-flex d-sm-block d-md-flex align-items-center">
              <h2 class="mb-0">50</h2>
              <p class="text-success ms-2 mb-0 font-weight-medium">+8,3%</p>
            </div>
            <h6 class="text-muted font-weight-normal">+9,61% depuis le mois dernier</h6>
          </div>
          <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
            <i class="icon-lg mdi mdi-account-check text-danger ms-auto"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-4 grid-margin">
    <div class="card">
      <div class="card-body">
        <h5>Total paiements</h5>
        <div class="row">
          <div class="col-8 col-sm-12 col-xl-8 my-auto">
            <div class="d-flex d-sm-block d-md-flex align-items-center">
              <h2 class="mb-0">180 000 FCFA</h2>
              <p class="text-danger ms-2 mb-0 font-weight-medium">-2,1%</p>
            </div>
            <h6 class="text-muted font-weight-normal">-2,27% depuis le mois dernier</h6>
          </div>
          <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
            <i class="icon-lg mdi mdi-cash text-success ms-auto"></i>
          </div>
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
                  <option value="Formation Laravel">Formation Laravel</option>
                  <option value="Formation JavaScript">Formation JavaScript</option>
                  <option value="Pack complet Web">Pack complet Web</option>
                </select>
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
        
        <!-- Tableau des inscriptions -->
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
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>#0001</td>
                <td>Henry Klein</td>
                <td>Formation Laravel</td>
                <td>20 000 FCFA</td>
                <td>25/07/2025</td>
                <td>Carte bancaire</td>
                <td><span class="badge badge-outline-success">Payé</span></td>
                <td>
                  <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-info btn-icon" title="Détails paiement">
                      <i class="mdi mdi-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-warning btn-icon" title="Modifier">
                      <i class="mdi mdi-pencil"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>#0002</td>
                <td>Estella Bryan</td>
                <td>Formation JavaScript</td>
                <td>15 000 FCFA</td>
                <td>Aucun</td>
                <td>Aucun</td>
                <td><span class="badge badge-outline-warning">En attente</span></td>
                <td>
                  <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-info btn-icon" title="Détails paiement">
                      <i class="mdi mdi-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-warning btn-icon" title="Modifier">
                      <i class="mdi mdi-pencil"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>#0003</td>
                <td>Peter Gill</td>
                <td>Pack complet Web</td>
                <td>30 000 FCFA</td>
                <td>Aucun</td>
                <td>Aucun</td>
                <td><span class="badge badge-outline-danger">Rejeté</span></td>
                <td>
                  <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-info btn-icon" title="Détails paiement">
                      <i class="mdi mdi-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-warning btn-icon" title="Modifier">
                      <i class="mdi mdi-pencil"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>#0004</td>
                <td>Marie Dupont</td>
                <td>Marketing Digital</td>
                <td>25 000 FCFA</td>
                <td>28/07/2025</td>
                <td>Mobile Money</td>
                <td><span class="badge badge-outline-success">Payé</span></td>
                <td>
                  <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-info btn-icon" title="Détails paiement">
                      <i class="mdi mdi-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-warning btn-icon" title="Modifier">
                      <i class="mdi mdi-pencil"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>#0005</td>
                <td>Jean Martin</td>
                <td>Développement Web</td>
                <td>30 000 FCFA</td>
                <td>15/07/2025</td>
                <td>Virement bancaire</td>
                <td><span class="badge badge-outline-success">Payé</span></td>
                <td>
                  <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-info btn-icon" title="Détails paiement">
                      <i class="mdi mdi-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-warning btn-icon" title="Modifier">
                      <i class="mdi mdi-pencil"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Pagination fonctionnelle -->
        <div class="d-flex justify-content-between align-items-center mt-4">
          <div class="text-muted">Affichage de 1 à 5 sur 50 inscriptions</div>
          <nav aria-label="Page navigation">
            <ul class="pagination mb-0">
              <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1">Précédent</a>
              </li>
              <li class="page-item active"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item"><a class="page-link" href="#">4</a></li>
              <li class="page-item"><a class="page-link" href="#">5</a></li>
              <li class="page-item">
                <a class="page-link" href="#">Suivant</a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Détails Paiement -->
<div class="modal fade" id="paymentDetailsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Détails du paiement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-4">
          <h6>Henry Klein - Formation Laravel</h6>
          <p><strong>Montant total:</strong> 20 000 FCFA</p>
        </div>
        
        <div class="table-responsive">
          <table class="table table-sm">
            <thead>
              <tr>
                <th>Date</th>
                <th>Montant</th>
                <th>Méthode</th>
                <th>Référence</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>25/07/2025</td>
                <td>20 000 FCFA</td>
                <td>Carte bancaire</td>
                <td>REF-789456</td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-3">
          <strong>Total payé: 20 000 FCFA</strong>
          <div class="badge badge-success">Complet</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        <a href="{{ route('newPayment') }}" class="btn btn-primary">Ajouter un paiement</a>
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
</style>
@endpush

@push('scripts')
<script>
  // Script pour le modal de détails des paiements
  document.addEventListener('DOMContentLoaded', function() {
    const paymentButtons = document.querySelectorAll('.btn-info');
    
    paymentButtons.forEach(button => {
      button.addEventListener('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('paymentDetailsModal'));
        modal.show();
      });
    });
    
    // Filtrage des inscriptions
    const filterStatus = document.getElementById('filter-status');
    const filterFormation = document.getElementById('filter-formation');
    const searchInput = document.getElementById('search-input');
    const tableRows = document.querySelectorAll('tbody tr');
    
    function filterRows() {
      const statusValue = filterStatus.value.toLowerCase();
      const formationValue = filterFormation.value.toLowerCase();
      const searchValue = searchInput.value.toLowerCase();
      
      tableRows.forEach(row => {
        const statusCell = row.querySelector('td:nth-child(7) span').textContent.toLowerCase();
        const formationCell = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        const nameCell = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        
        const statusMatch = statusValue === '' || statusCell.includes(statusValue);
        const formationMatch = formationValue === '' || formationCell.includes(formationValue);
        const searchMatch = nameCell.includes(searchValue) || formationCell.includes(searchValue);
        
        if (statusMatch && formationMatch && searchMatch) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    }
    
    filterStatus.addEventListener('change', filterRows);
    filterFormation.addEventListener('change', filterRows);
    searchInput.addEventListener('input', filterRows);
  });
</script>
@endpush