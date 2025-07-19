@extends('layouts.sAdApp')

@section('title', 'Inscriptions - Tableau de Bord')
@section('page-title', 'Liste des Inscriptions')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
  <li class="breadcrumb-item active" aria-current="page">Inscriptions</li>
@endsection

@section('inscription2', 'active')

@section('content')
@include('components.alert-adModal')

<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Statut des Inscriptions</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    <div class="form-check form-check-muted m-0">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" id="select-all">
                                        </label>
                                    </div>
                                </th>
                                <th> Client </th>
                                <th> Formation </th>
                                <th> Montant </th>
                                <th> Contact </th>
                                <th> Date </th>
                                <th> Statut </th>
                                <th> Actions </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allInsc as $eachInsc)
                            <tr>
                                <td>
                                    <div class="form-check form-check-muted m-0">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input select-checkbox" data-id="{{ $eachInsc->id }}">
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <img src="{{ asset('admin/assets/images/faces/face1.jpg') }}" alt="image" class="user-avatar" />
                                    <span class="ps-2">{{ $eachInsc->name }}</span>
                                </td>
                                <td>{{ $eachInsc->formation->titre ?? 'Formation supprimée' }}</td>
                                <td>{{ number_format($eachInsc->montant, 0, ',', ' ') }} FCFA</td>
                                <td>
                                    <div>{{ $eachInsc->email }}</div>
                                    <div class="text-muted small">{{ $eachInsc->phone }}</div>
                                </td>
                                <td>{{ $eachInsc->created_at->format('d M Y') }}</td>
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
                                        <a href="{{ route('accepterRes', $eachInsc->id) }}" 
                                           class="btn btn-sm btn-success btn-icon"
                                           @if($eachInsc->status != 'En attente') disabled @endif
                                           title="Accepter">
                                            <i class="mdi mdi-check"></i>
                                        </a>
                                        <a href="{{ route('rejeterRes', $eachInsc->id) }}" 
                                           class="btn btn-sm btn-danger btn-icon"
                                           onclick="return confirmAction('rejeter')"
                                           @if($eachInsc->status != 'En attente') disabled @endif
                                           title="Rejeter">
                                            <i class="mdi mdi-close"></i>
                                        </a>
                                        <a href="#" 
                                           class="btn btn-sm btn-info btn-icon view-message"
                                           data-message="{{ $eachInsc->message }}"
                                           title="Voir message">
                                            <i class="mdi mdi-message-text"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Actions groupées -->
                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <div>
                        <select class="form-select form-select-sm" id="bulk-action">
                            <option value="">Action groupée</option>
                            <option value="accept">Accepter sélection</option>
                            <option value="reject">Rejeter sélection</option>
                        </select>
                    </div>
                    <div>
                        <button class="btn btn-primary btn-sm" id="apply-bulk-action">Appliquer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour afficher les messages -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Message du client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="message-content">
                <!-- Le contenu du message sera injecté ici -->
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
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de la sélection/désélection globale
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.select-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Affichage des messages
        document.querySelectorAll('.view-message').forEach(button => {
            button.addEventListener('click', function() {
                const message = this.getAttribute('data-message');
                document.getElementById('message-content').textContent = 
                    message || "Aucun message n'a été laissé par le client";
                
                const modal = new bootstrap.Modal(document.getElementById('messageModal'));
                modal.show();
            });
        });

        // Confirmation pour le rejet
        window.confirmAction = function(action) {
            return confirm(`Souhaitez-vous réellement ${action} cette demande ?`);
        }

        // Actions groupées
        document.getElementById('apply-bulk-action').addEventListener('click', function() {
            const selectedIds = [];
            document.querySelectorAll('.select-checkbox:checked').forEach(checkbox => {
                selectedIds.push(checkbox.getAttribute('data-id'));
            });

            if (selectedIds.length === 0) {
                alert('Veuillez sélectionner au moins une inscription');
                return;
            }

            const action = document.getElementById('bulk-action').value;
            if (!action) {
                alert('Veuillez sélectionner une action');
                return;
            }

            if (confirm(`Confirmez-vous cette action pour ${selectedIds.length} inscription(s) ?`)) {
                // Ici, vous enverriez normalement une requête AJAX ou un formulaire
                // Pour l'exemple, nous allons juste rediriger
                if (action === 'accept') {
                    window.location.href = `/bulk-accept?ids=${selectedIds.join(',')}`;
                } else if (action === 'reject') {
                    window.location.href = `/bulk-reject?ids=${selectedIds.join(',')}`;
                }
            }
        });
    });
</script>
@endpush