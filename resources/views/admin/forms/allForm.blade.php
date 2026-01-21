@extends('layouts.sAdApp')

@section('title', 'Formations - Tableau de Bord')
@section('page-title', 'Formations')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active" aria-current="page">Formations</li>
@endsection

@section('formation', 'active')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="card-text d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Liste des formations</h4>
                    <a href="{{ route('newForm') }}" class="btn btn-primary addLink">
                        <i class="mdi mdi-plus-circle me-2"></i>Ajouter une formation
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Titre</th>
                                <th>Catégorie</th>
                                <th>Niveau</th>
                                <th>Status</th>
                                <th>Prix (FCFA)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($forms as $form)
                            <tr>
                                <td>
                                    <img src="{{ asset($form->image_url) }}" 
                                         alt="{{ $form->titre }}" 
                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                </td>
                                <td>{{ $form->titre }}</td>
                                <td><span class="badge bg-secondary">{{ ucfirst($form->categorie) }}</span></td>
                                <td>{{ ucfirst($form->niveau) }}</td>
                                <td>
                                    @if($form->status === 'publiee')
                                        <span class="badge bg-success">Publiée</span>
                                    @elseif($form->status === 'brouillon')
                                        <span class="badge bg-warning text-dark">Brouillon</span>
                                    @elseif($form->status === 'archivee')
                                        <span class="badge bg-secondary">Archivée</span>
                                    @endif
                                </td>
                                <td>{{ number_format($form->prix, 0, ',', ' ') }}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Actions">
                                        <!-- Bouton Voir détails -->
                                        <button type="button" 
                                                title="Voir détails" 
                                                class="btn btn-sm btn-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#formationDetailsModal"
                                                data-titre="{{ $form->titre }}"
                                                data-description="{{ $form->description_longue ?? 'Aucune description disponible' }}"
                                                data-categorie="{{ ucfirst($form->categorie) }}"
                                                data-niveau="{{ ucfirst($form->niveau) }}"
                                                data-duree="{{ $form->duree_mois }} mois"
                                                data-prix="{{ number_format($form->prix, 0, ',', ' ') }} FCFA"
                                                data-places="{{ $form->places_disponibles ?? 'Illimitées' }}"
                                                data-debut="{{ $form->date_debut ? \Carbon\Carbon::parse($form->date_debut)->format('d/m/Y') : 'Non définie' }}"
                                                data-fin="{{ $form->date_fin ? \Carbon\Carbon::parse($form->date_fin)->format('d/m/Y') : 'Non définie' }}"
                                                data-status="{{ $form->status === 'publiee' ? 'Publiée' : ($form->status === 'brouillon' ? 'Brouillon' : 'Archivée') }}"
                                                data-image="{{ asset($form->image_url) }}"
                                                onclick="loadFormationDetails(this)">
                                            <i class="mdi mdi-eye"></i>
                                        </button>
                                        
                                        <!-- Bouton Modifier -->
                                        <a href="{{ route('updateView', ['id' => $form->id]) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Modifier">
                                            <i class="mdi mdi-border-color"></i>
                                        </a>
                                        
                                        <!-- Bouton Supprimer -->
                                        <form action="{{ route('supForm', ['id' => $form->id]) }}" 
                                              method="GET" 
                                              class="d-inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette formation ? Cette action est irréversible.')">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    title="Supprimer">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal détails de la formation -->
<div class="modal fade" id="formationDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails de la formation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-4 text-center">
                        <img id="detailImage" src="" alt="Image de la formation" class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                    <div class="col-md-8">
                        <h4 id="detailTitre" class="mb-3"></h4>
                        <p id="detailDescriptionLongue" class="text-muted"></p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Catégorie :</strong> 
                                <span id="detailCategorie" class="badge bg-secondary"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Niveau :</strong> 
                                <span id="detailNiveau"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Durée :</strong> 
                                <span id="detailDuree"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Prix :</strong> 
                                <span id="detailPrix"></span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Places disponibles :</strong> 
                                <span id="detailPlaces"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Date de début :</strong> 
                                <span id="detailDebut"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Date de fin :</strong> 
                                <span id="detailFin"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Status :</strong> 
                                <span id="detailStatus" class="badge"></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function loadFormationDetails(el) {
        const data = el.dataset;
        
        document.getElementById('detailTitre').textContent = data.titre || 'N/A';
        document.getElementById('detailDescriptionLongue').textContent = data.description || 'Pas de description disponible';
        document.getElementById('detailCategorie').textContent = data.categorie || 'N/A';
        document.getElementById('detailNiveau').textContent = data.niveau || 'N/A';
        document.getElementById('detailDuree').textContent = data.duree || 'N/A';
        document.getElementById('detailPrix').textContent = data.prix || 'N/A';
        document.getElementById('detailPlaces').textContent = data.places || 'N/A';
        document.getElementById('detailDebut').textContent = data.debut || 'N/A';
        document.getElementById('detailFin').textContent = data.fin || 'N/A';
        
        // Gérer le badge de status
        const statusElement = document.getElementById('detailStatus');
        statusElement.textContent = data.status || 'N/A';
        statusElement.className = 'badge ';
        
        switch(data.status) {
            case 'Publiée':
                statusElement.classList.add('bg-success');
                break;
            case 'Brouillon':
                statusElement.classList.add('bg-warning', 'text-dark');
                break;
            case 'Archivée':
                statusElement.classList.add('bg-secondary');
                break;
            default:
                statusElement.classList.add('bg-secondary');
        }
        
        document.getElementById('detailImage').src = data.image || '';
    }

    // Gérer la confirmation de suppression
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('form[onsubmit*="confirm"]');
        
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const confirmMessage = this.getAttribute('onsubmit').match(/return confirm\('([^']+)'\)/);
                if (confirmMessage && !confirm(confirmMessage[1])) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush