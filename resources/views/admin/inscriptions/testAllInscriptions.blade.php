@extends('layouts.sAdApp')

@section('title', 'Inscriptions - Tableau de Bord')
@section('page-title', 'Inscriptions')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
  <li class="breadcrumb-item active" aria-current="page">Inscriptions</li>
@endsection

@section('inscription', 'active')

@section('content')
@include('components.alert-adModal')

<style>
    :root {
        --bg-primary: #1a202c;
        --bg-secondary: #2d3748;
        --bg-tertiary: #4a5568;
        --accent-primary: #4299e1;
        --accent-success: #48bb78;
        --accent-warning: #ed8936;
        --accent-danger: #f56565;
        --text-primary: #e2e8f0;
        --text-secondary: #a0aec0;
        --border-primary: #4a5568;
        --border-secondary: #718096;
    }
    
    .card {
        background-color: var(--bg-primary);
        border: 1px solid var(--border-primary);
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .card-header {
        background-color: var(--bg-secondary);
        border-bottom: 1px solid var(--border-primary);
        padding: 1rem 1.5rem;
    }
    
    .card-title {
        color: var(--text-primary);
        margin-bottom: 0;
        font-weight: 600;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .table-dark-custom {
        --bs-table-bg: var(--bg-secondary);
        --bs-table-striped-bg: rgba(45, 55, 72, 0.5);
        --bs-table-striped-color: var(--text-primary);
        --bs-table-active-bg: var(--bg-tertiary);
        --bs-table-active-color: var(--text-primary);
        --bs-table-hover-bg: rgba(66, 153, 225, 0.1);
        --bs-table-hover-color: var(--text-primary);
        color: var(--text-primary);
        border-color: var(--border-primary);
    }
    
    .table-dark-custom > thead {
        background-color: var(--bg-tertiary);
        color: var(--text-primary);
    }
    
    .table-dark-custom th {
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.85rem;
        padding: 0.75rem 1rem;
    }
    
    .table-dark-custom td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
        border-top: 1px solid var(--border-primary);
    }
    
    .form-control, .form-select {
        background-color: var(--bg-secondary);
        border: 1px solid var(--border-primary);
        color: var(--text-primary);
        padding: 0.5rem 1rem;
    }
    
    .form-control:focus, .form-select:focus {
        background-color: var(--bg-secondary);
        border-color: var(--accent-primary);
        color: var(--text-primary);
        box-shadow: 0 0 0 0.2rem rgba(66, 153, 225, 0.25);
    }
    
    .input-group-text {
        background-color: var(--bg-tertiary);
        border: 1px solid var(--border-primary);
        color: var(--text-secondary);
    }
    
    .badge {
        padding: 0.5em 0.8em;
        font-weight: 600;
        letter-spacing: 0.03em;
    }
    
    .btn {
        font-weight: 500;
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        transition: all 0.2s ease;
    }
    
    .btn-primary {
        background-color: var(--accent-primary);
        border-color: var(--accent-primary);
    }
    
    .btn-primary:hover {
        background-color: #3182ce;
        border-color: #3182ce;
    }
    
    .btn-outline-info {
        color: #63b3ed;
        border-color: #63b3ed;
    }
    
    .btn-outline-info:hover {
        background-color: rgba(99, 179, 237, 0.1);
    }
    
    .btn-outline-success {
        color: var(--accent-success);
        border-color: var(--accent-success);
    }
    
    .btn-outline-success:hover {
        background-color: rgba(72, 187, 120, 0.1);
    }
    
    .btn-outline-danger {
        color: var(--accent-danger);
        border-color: var(--accent-danger);
    }
    
    .btn-outline-danger:hover {
        background-color: rgba(245, 101, 101, 0.1);
    }
    
    .pagination-dark .page-item .page-link {
        background-color: var(--bg-secondary);
        border: 1px solid var(--border-primary);
        color: var(--text-primary);
    }
    
    .pagination-dark .page-item.active .page-link {
        background-color: var(--accent-primary);
        border-color: var(--accent-primary);
    }
    
    .pagination-dark .page-item.disabled .page-link {
        background-color: var(--bg-tertiary);
        color: var(--text-secondary);
    }
    
    .filter-card {
        background-color: var(--bg-secondary);
        border: 1px solid var(--border-primary);
    }
    
    .filter-title {
        color: var(--accent-primary);
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .section-title {
        font-weight: 600;
        font-size: 1.2rem;
        margin-bottom: 1.5rem;
        color: var(--text-primary);
        position: relative;
        padding-bottom: 0.5rem;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 3px;
        background: var(--accent-primary);
        border-radius: 3px;
    }
    
    .stats-badge {
        background-color: rgba(66, 153, 225, 0.15);
        color: var(--accent-primary);
    }
    
    .modal-dark .modal-content {
        background-color: var(--bg-primary);
        border: 1px solid var(--border-primary);
        color: var(--text-primary);
    }
    
    .modal-dark .modal-header {
        background-color: var(--bg-secondary);
        border-bottom: 1px solid var(--border-primary);
    }
    
    .modal-dark .modal-footer {
        background-color: var(--bg-secondary);
        border-top: 1px solid var(--border-primary);
    }
    
    .list-group-item-dark {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
        border: 1px solid var(--border-primary);
    }
    
    .divider {
        height: 1px;
        background-color: var(--border-primary);
        margin: 1.5rem 0;
    }
    
    .text-light {
        color: var(--text-primary) !important;
    }
    
    .bg-gray-800 {
        background-color: var(--bg-secondary) !important;
    }
    
    .border-secondary {
        border-color: var(--border-primary) !important;
    }
    
    .bg-dark {
        background-color: var(--bg-primary) !important;
    }
    
    .bg-gray-700 {
        background-color: var(--bg-tertiary) !important;
    }
    
    .text-info {
        color: var(--accent-primary) !important;
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0 text-light">Inscriptions</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-light">Dashboard</a></li>
                    <li class="breadcrumb-item active text-light" aria-current="page">Inscriptions</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('inscView') }}" class="btn btn-primary">
            <i class="mdi mdi-plus me-2"></i> Ajouter une inscription
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0 text-light"><i class="mdi mdi-filter-outline me-2"></i>Filtres</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.inscriptions') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filtreNom" class="form-label text-light">Nom</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="mdi mdi-account"></i>
                                </span>
                                <input type="text" id="filtreNom" name="nom" value="{{ request('nom') }}" 
                                    class="form-control" placeholder="Rechercher par nom">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filtreDate" class="form-label text-light">Date d'inscription</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="mdi mdi-calendar"></i>
                                </span>
                                <input type="date" id="filtreDate" name="date" 
                                    value="{{ request('date') }}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtreStatut" class="form-label text-light">Statut</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="mdi mdi-flag"></i>
                                </span>
                                <select name="statut" id="filtreStatut" class="form-select">
                                    <option value="">Tous</option>
                                    <option value="En attente" {{ request('statut') == 'En attente' ? 'selected' : '' }}>En attente</option>
                                    <option value="Accepté" {{ request('statut') == 'Accepté' ? 'selected' : '' }}>Accepté</option>
                                    <option value="Rejeté" {{ request('statut') == 'Rejeté' ? 'selected' : '' }}>Rejeté</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtreTri" class="form-label text-light">Trier par montant</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="mdi mdi-sort"></i>
                                </span>
                                <select name="tri" id="filtreTri" class="form-select">
                                    <option value="">--</option>
                                    <option value="asc" {{ request('tri') == 'asc' ? 'selected' : '' }}>Croissant</option>
                                    <option value="desc" {{ request('tri') == 'desc' ? 'selected' : '' }}>Décroissant</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="mdi mdi-filter me-1"></i> Appliquer
                        </button>
                        <a href="{{ route('admin.inscriptions') }}" class="btn btn-outline-light w-100" title="Réinitialiser">
                            <i class="mdi mdi-refresh"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            @if($allInsc->total() > 0)
                <span class="me-3 text-light">Total: <strong>{{ $allInsc->total() }}</strong> inscriptions</span>
                @php
                    $accepted = $allInsc->where('status', 'Accepté')->count();
                    $pending = $allInsc->where('status', 'En attente')->count();
                    $rejected = $allInsc->where('status', 'Rejeté')->count();
                @endphp
                <span class="badge bg-success me-2">Accepté: {{ $accepted }}</span>
                <span class="badge bg-warning me-2">En attente: {{ $pending }}</span>
                <span class="badge bg-danger">Rejeté: {{ $rejected }}</span>
            @else
                <span class="text-light">Aucune inscription trouvée</span>
            @endif
        </div>
        
        @if(request()->hasAny(['nom', 'date', 'statut', 'tri']))
            <div class="d-flex align-items-center">
                <span class="badge stats-badge me-2">Filtres actifs</span>
                <a href="{{ route('admin.inscriptions') }}" class="btn btn-sm btn-outline-light">
                    <i class="mdi mdi-close me-1"></i> Effacer
                </a>
            </div>
        @endif
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-light">Liste des Inscriptions</h5>
            <div>
                @if($allInsc->total() > 0)
                    <span class="text-light me-2">
                        Affichage de {{ $allInsc->firstItem() }} à {{ $allInsc->lastItem() }} sur {{ $allInsc->total() }}
                    </span>
                @endif
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark-custom table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="text-light">Nom</th>
                            <th class="text-light">Email</th>
                            <th class="text-light">Montant</th>
                            <th class="text-light">Formation</th>
                            <th class="text-light">Statut</th>
                            <th class="text-light">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allInsc as $eachInsc)
                        <tr>
                            <td>{{ $eachInsc->name }}</td>
                            <td>{{ $eachInsc->email }}</td>
                            <td>{{ number_format($eachInsc->montant, 0, ',', ' ') }} FCFA</td>
                            <td>{{ $eachInsc->choixForm }}</td>                               
                            <td>
                                @if($eachInsc->status == 'Accepté')
                                    <span class="badge bg-success">Accepté</span>
                                @elseif($eachInsc->status == 'Rejeté')
                                    <span class="badge bg-danger">Rejeté</span>
                                @else
                                    <span class="badge bg-warning text-dark">En attente</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="#" title="Voir détails" class="btn btn-sm btn-outline-info" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#inscriptionDetailsModal"
                                        data-name="{{ $eachInsc->name }}"
                                        data-email="{{ $eachInsc->email }}"
                                        data-phone="{{ $eachInsc->phone }}"
                                        data-address="{{ $eachInsc->address }}"
                                        data-message="{{ $eachInsc->message }}"
                                        data-montant="{{ $eachInsc->montant }}"
                                        data-choix="{{ $eachInsc->choixForm }}"
                                        data-date="{{ $eachInsc->created_at->format('d/m/Y') }}"
                                        data-status="{{ $eachInsc->status }}"
                                        onclick="loadInscriptionDetails(this)">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                    <a href="{{ route('accepterRes', $eachInsc->id) }}" 
                                    class="btn btn-sm btn-outline-success"
                                    @if($eachInsc->status != 'En attente') disabled @endif
                                    title="Accepter">
                                        <i class="mdi mdi-check"></i>
                                    </a>
                                    <a href="{{ route('rejeterRes', $eachInsc->id) }}" 
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Souhaitez-vous réellement rejeter cette demande ?')"
                                    @if($eachInsc->status != 'En attente') disabled @endif
                                    title="Rejeter">
                                        <i class="mdi mdi-close"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-light">
                                <i class="mdi mdi-database-remove me-2"></i>
                                Aucune inscription trouvée avec ces critères
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <div class="text-light">
                @if($allInsc->total() > 0)
                    Affichage de {{ $allInsc->firstItem() }} à {{ $allInsc->lastItem() }} sur {{ $allInsc->total() }}
                @else
                    Aucune inscription
                @endif
            </div>
            @if($allInsc->hasPages())
            <nav>
                {{ $allInsc->onEachSide(1)->links('pagination::bootstrap-4-dark') }}
            </nav>
            @endif
        </div>
    </div>
</div>

<!-- Modal détails de l'inscription avec style sombre -->
<div class="modal fade modal-dark" id="inscriptionDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-light">
                    <i class="mdi mdi-card-account-details-outline me-2 text-info"></i>
                    Détails de l'inscription
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <h6 class="text-info mb-3">Informations personnelles</h6>
                            <ul class="list-group">
                                <li class="list-group-item list-group-item-dark d-flex justify-content-between">
                                    <strong class="text-light">Nom complet :</strong> 
                                    <span id="detailNom" class="text-light"></span>
                                </li>
                                <li class="list-group-item list-group-item-dark d-flex justify-content-between">
                                    <strong class="text-light">Email :</strong> 
                                    <span id="detailEmail" class="text-light"></span>
                                </li>
                                <li class="list-group-item list-group-item-dark d-flex justify-content-between">
                                    <strong class="text-light">Téléphone :</strong> 
                                    <span id="detailTel" class="text-light"></span>
                                </li>
                                <li class="list-group-item list-group-item-dark d-flex justify-content-between">
                                    <strong class="text-light">Adresse :</strong> 
                                    <span id="detailAdresse" class="text-light"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <h6 class="text-info mb-3">Détails de l'inscription</h6>
                            <ul class="list-group">
                                <li class="list-group-item list-group-item-dark d-flex justify-content-between">
                                    <strong class="text-light">Prix :</strong> 
                                    <span id="detailMontant" class="text-light"></span> FCFA
                                </li>
                                <li class="list-group-item list-group-item-dark d-flex justify-content-between">
                                    <strong class="text-light">Formation :</strong> 
                                    <span id="detailChoix" class="text-light"></span>
                                </li>
                                <li class="list-group-item list-group-item-dark d-flex justify-content-between">
                                    <strong class="text-light">Date d'inscription :</strong> 
                                    <span id="detailDate" class="text-light"></span>
                                </li>
                                <li class="list-group-item list-group-item-dark d-flex justify-content-between">
                                    <strong class="text-light">Status :</strong> 
                                    <span id="detailStatus" class="text-light"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="divider"></div>
                
                <div class="mb-3">
                    <h6 class="text-info mb-3">Message</h6>
                    <div class="card bg-gray-800 border-secondary">
                        <div class="card-body">
                            <p id="detailMessage" class="mb-0 text-light"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-1"></i> Fermer
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function loadInscriptionDetails(el) {
        // Mise à jour des éléments du modal
        document.getElementById('detailNom').textContent = el.dataset.name || 'N/A';
        document.getElementById('detailEmail').textContent = el.dataset.email || 'N/A';
        document.getElementById('detailTel').textContent = el.dataset.phone || 'N/A';
        document.getElementById('detailAdresse').textContent = el.dataset.address || 'N/A';
        document.getElementById('detailMessage').textContent = el.dataset.message || 'Pas de message disponible';
        document.getElementById('detailDate').textContent = el.dataset.date || 'N/A';
        document.getElementById('detailMontant').textContent = el.dataset.montant || 'N/A';
        document.getElementById('detailChoix').textContent = el.dataset.choix || 'N/A';
        document.getElementById('detailStatus').textContent = el.dataset.status || 'N/A';
    }
</script>
@endpush