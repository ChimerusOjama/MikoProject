@extends('layouts.sAdApp')

@section('title', 'Inscriptions - Tableau de Bord')
@section('page-title', 'Inscriptions')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
  <li class="breadcrumb-item active" aria-current="page">Inscriptions</li>
@endsection

@section('inscription', 'active')

@section('content')
                    @if(session('message'))
                      <div class="alert alert-warning alert-dismissible fade show" role="alert">
                          <strong>Attention!</strong> {{ session('message') }}.
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                    @elseif(session('message2'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Succès!</strong> Vous avez accepte la demande.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @else

                    @endif
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Liste des Inscriptions</h4>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Email</th>
                                            <th>Téléphone</th>
                                            <th>Adresse</th>
                                            <th>Montant</th>
                                            <th>Choix</th>
                                            <th>Message</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allInsc as $eachInsc)
                                        <tr>
                                            <td>{{ $eachInsc->name }}</td>
                                            <td>{{ $eachInsc->email }}</td>
                                            <td>{{ $eachInsc->phone }}</td>
                                            <td>{{ $eachInsc->address }}</td>
                                            <td>{{ $eachInsc->montant }}</td>
                                            <td>{{ $eachInsc->choixForm }}</td>
                                            <td>{{ $eachInsc->message }}</td>
                                            <td><label class="text">{{ $eachInsc->status }}</label></td>
                                            <td>
                                                <a href="/Accepter_reservation/inscription={{ $eachInsc->id }}" class="badge badge-success">Accepter</a>
                                                <a href="/Rejeter_reservation/inscription={{ $eachInsc->id }}" 
                                                class="badge badge-danger"
                                                onclick="return confirm('Souhaitez-vous réellement rejeter cette demande ?')">Refuser</a>
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