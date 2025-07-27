@extends('layouts.sAdApp')

@section('title', 'Formations - Tableau de Bord')
@section('page-title', 'Formations')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
  <li class="breadcrumb-item active" aria-current="page">Utilisateurs</li>
@endsection

@section('utilisateurs', 'active')

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
                                <h4 class="card-title">Liste des utilisateurs</h4>
                                <a href="{{ route('newUser') }}" class="btn btn-primary addLink">Ajouter un utilisateur</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Photo de profil</th>
                                            <th>Nom complet</th>
                                            <th>Email</th>
                                            <th>Rôle</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                        <tr>
                                            <!-- <td><img src="{{ asset($user->profile_photo_path) }}" alt="" srcset=""></td> -->
                                            <td><img src="{{ asset('admin/assets/images/faces/face21.jpg') }}" alt="" srcset=""></td>
                                            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->usertype }}</td>
                                            <td>
                                                <a href="/Modifier_utilisateur/utilisateur={{ $user->id }}" class="badge badge-info">Mettre à jour</a>
                                                <a href="/Supprimer_utilisateur/utilisateur={{ $user->id }}" 
                                                class="badge badge-danger"
                                                onclick="return confirm('Souhaitez-vous réellement supprimer cet utilisateur ?')">Supprimer</a>
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