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

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title mb-0">Liste des Inscriptions</h4>
                <a href="{{ route('inscView') }}" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i> Ajouter une inscription
                </a>
            </div>

            <p class="card-text">Vous pouvez filtrer et trier les inscriptions selon vos besoins.</p>
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.inscriptions') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label for="filtreNom" class="form-label">Nom</label>
                                <input type="text" id="filtreNom" name="nom" value="{{ request('nom') }}" class="form-control" placeholder="Nom">
                            </div>

                            <div class="col-md-3">
                                <label for="filtreDate" class="form-label">Date d'inscription</label>
                                <input type="date" id="filtreDate" name="date" value="{{ request('date') }}" class="form-control">
                            </div>

                            <div class="col-md-2">
                                <label for="filtreStatut" class="form-label">Statut</label>
                                <select name="statut" id="filtreStatut" class="form-select">
                                    <option value="">Tous</option>
                                    <option value="En attente" {{ request('statut') == 'En attente' ? 'selected' : '' }}>En attente</option>
                                    <option value="Accepté" {{ request('statut') == 'Accepté' ? 'selected' : '' }}>Accepté</option>
                                    <option value="Rejeté" {{ request('statut') == 'Rejeté' ? 'selected' : '' }}>Rejeté</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="filtreTri" class="form-label">Trier par montant</label>
                                <select name="tri" id="filtreTri" class="form-select">
                                    <option value="">--</option>
                                    <option value="asc" {{ request('tri') == 'asc' ? 'selected' : '' }}>Croissant</option>
                                    <option value="desc" {{ request('tri') == 'desc' ? 'selected' : '' }}>Décroissant</option>
                                </select>
                            </div>

                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-outline-primary w-100">
                                    <i class="mdi mdi-filter"></i> Filtrer
                                </button>
                                <a href="{{ route('admin.inscriptions') }}" class="btn btn-outline-secondary w-100">
                                    <i class="mdi mdi-refresh"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>



            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Montant</th>
                            <th>Choix</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allInsc as $eachInsc)
                        <tr>
                            <td>{{ $eachInsc->name }}</td>
                            <td>{{ $eachInsc->email }}</td>
                            <td>{{ $eachInsc->montant }}</td>
                            <td>{{ $eachInsc->choixForm }}</td>                               
                            <td>
                                @if($eachInsc->status == 'Accepté')
                                    <div class="badge badge-outline-success">Accepté</div>
                                @elseif($eachInsc->status == 'Rejeté')
                                    <div class="badge badge-outline-danger">Rejeté</div>
                                @else
                                    <div class="badge badge-outline-warning">En attente</div>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="#" title="Voir détails" class="badge badge-primary" 
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
                                    class="btn btn-sm btn-success btn-icon"
                                    @if($eachInsc->status != 'En attente') disabled @endif
                                    title="Accepter">
                                        <i class="mdi mdi-check"></i>
                                    </a>
                                    <a href="{{ route('rejeterRes', $eachInsc->id) }}" 
                                    class="btn btn-sm btn-danger btn-icon"
                                    onclick="return confirm('Souhaitez-vous réellement rejeter cette demande ?')"
                                    @if($eachInsc->status != 'En attente') disabled @endif
                                    title="Rejeter">
                                        <i class="mdi mdi-close"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        @endforeach


                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $allInsc->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal détails de l'inscription -->
<div class="modal fade" id="inscriptionDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails de l'inscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h4 id="detailTitre">Détails de l'inscription</h4>
                    <p id="detailMessage" class="text-muted"></p>
                </div>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Nom complet :</strong> <span id="detailNom"></span></li>
                    <li class="list-group-item"><strong>Email :</strong> <span id="detailEmail"></span></li>
                    <li class="list-group-item"><strong>Téléphone :</strong> <span id="detailTel"></span></li> {{-- Ajouté --}}
                    <li class="list-group-item"><strong>Adresse :</strong> <span id="detailAdresse"></span></li> {{-- Ajouté --}}
                    <li class="list-group-item"><strong>Prix :</strong> <span id="detailMontant"></span> FCFA</li>
                    <li class="list-group-item"><strong>Formation choisie :</strong> <span id="detailChoix"></span></li>
                    <li class="list-group-item"><strong>Date d'inscription :</strong> <span id="detailDate"></span></li>
                    <li class="list-group-item"><strong>Status :</strong> <span id="detailStatus"></span></li>
                </ul>
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