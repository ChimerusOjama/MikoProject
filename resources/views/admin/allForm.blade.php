@extends('layouts.sAdApp')

@section('title', 'Formations - Tableau de Bord')
@section('page-title', 'Formations')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
  <li class="breadcrumb-item active" aria-current="page">Formations</li>
@endsection

@section('formation', 'active')

@section('content')
                  <div class="row">
                    @if(session('message'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Attention!</strong> {{ session('message') }}.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @elseif(session('message2'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Succès!</strong> {{ session('message2') }}.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @else

                    @endif
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
                                                <a href="/Modifier_formation/foramtion={{ $form->id }}" class="badge badge-info">Mettre à jour</a>
                                                <a href="/Supprimer_formation/foramtion={{ $form->id }}" 
                                                class="badge badge-danger"
                                                onclick="return confirm('Souhaitez-vous réellement supprimer cette formation ?')">Supprimer</a>
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
@endsection
@push('scripts')
  <script>
    function confirmAnnulation(id) {
        const form = document.getElementById('confirmForm');
        form.action = `/afficher-confirmation/${id}`;
        form.submit();
    }
  </script>
@endpush