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
                            <div class="card-text">
                                <h4 class="card-title">Liste des formations</h4>
                                <a href="{{ route('newForm') }}" class="btn btn-primary addLink">Ajouter une formation</a>
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
                                            <th>Prix</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($forms as $form)
                                        <tr>
                                            <td><img src="{{ asset($form->image_url) }}" alt="" srcset=""></td>
                                            <td>{{ $form->titre }}</td>
                                            <td>{{ $form->categorie }}</td>
                                            <td>{{ $form->niveau }}</td>
                                            <td><label class="text">{{ $form->status }}</label></td>
                                            <td>{{ $form->prix }}</td>
                                            <td>
                                                <a href="#" title="Voir détails" class="badge badge-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#formationDetailsModal"
                                                    data-titre="{{ $form->titre }}"
                                                    data-description="{{ $form->description_longue }}"
                                                    data-categorie="{{ $form->categorie }}"
                                                    data-niveau="{{ $form->niveau }}"
                                                    data-duree="{{ $form->duree_mois }}"
                                                    data-prix="{{ $form->prix }}"
                                                    data-places="{{ $form->places_disponibles }}"
                                                    data-debut="{{ $form->date_debut }}"
                                                    data-fin="{{ $form->date_fin }}"
                                                    data-status="{{ $form->status }}"
                                                    data-image="{{ asset($form->image_url) }}"
                                                    onclick="loadFormationDetails(this)">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                    <a href="/Modifier_formation/formation={{ $form->id }}" class="badge badge-info" title="Modifier"><i class="mdi mdi-border-color"></i></a>
                                                    <a href="/Supprimer_formation/formation={{ $form->id }}" 
                                                    class="badge badge-danger"
                                                    onclick="return confirm('Souhaitez-vous réellement supprimer cette formation ?')" title="Supprimer"><i class="mdi mdi-delete"></i></a>
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

                <!-- modal détails de la formation -->
                <div class="modal fade" id="formationDetailsModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Détails de la formation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                            <h4 id="detailTitre"></h4>
                            <p id="detailDescriptionLongue" class="text-muted"></p>
                            </div>
                            <ul class="list-group">
                            <li class="list-group-item"><strong>Catégorie :</strong> <span id="detailCategorie"></span></li>
                            <li class="list-group-item"><strong>Niveau :</strong> <span id="detailNiveau"></span></li>
                            <li class="list-group-item"><strong>Durée (mois) :</strong> <span id="detailDuree"></span></li>
                            <li class="list-group-item"><strong>Prix :</strong> <span id="detailPrix"></span> FCFA</li>
                            <li class="list-group-item"><strong>Places disponibles :</strong> <span id="detailPlaces"></span></li>
                            <li class="list-group-item"><strong>Date de début :</strong> <span id="detailDebut"></span></li>
                            <li class="list-group-item"><strong>Date de fin :</strong> <span id="detailFin"></span></li>
                            <li class="list-group-item"><strong>Status :</strong> <span id="detailStatus"></span></li>
                            </ul>
                            <div class="text-center mt-3">
                            <img id="detailImage" src="" alt="Image de la formation" class="img-fluid rounded" style="max-height: 200px;">
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
    function confirmAnnulation(id) {
        const form = document.getElementById('confirmForm');
        form.action = `/afficher-confirmation/${id}`;
        form.submit();
    }
  </script>
    <script>
    function loadFormationDetails(el) {
        document.getElementById('detailTitre').textContent = el.dataset.titre || 'N/A';
        document.getElementById('detailDescriptionLongue').textContent = el.dataset.description || 'Pas de description longue disponible';
        document.getElementById('detailCategorie').textContent = el.dataset.categorie || 'N/A';
        document.getElementById('detailNiveau').textContent = el.dataset.niveau || 'N/A';
        document.getElementById('detailDuree').textContent = el.dataset.duree || 'N/A';
        document.getElementById('detailPrix').textContent = el.dataset.prix || 'N/A';
        document.getElementById('detailPlaces').textContent = el.dataset.places || 'N/A';
        document.getElementById('detailDebut').textContent = el.dataset.debut || 'N/A';
        document.getElementById('detailFin').textContent = el.dataset.fin || 'N/A';
        document.getElementById('detailStatus').textContent = el.dataset.status || 'N/A';
        document.getElementById('detailImage').src = el.dataset.image || '';
    }

    </script>
@endpush
