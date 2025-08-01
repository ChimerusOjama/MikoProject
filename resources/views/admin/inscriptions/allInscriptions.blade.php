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
                            <h4 class="card-title">Liste des Inscriptions</h4>
                                <a href="{{ route('inscView') }}" class="btn btn-primary addLink">Inscription manuelle</a>
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
                                                    onclick="return confirm('Souhaitez-vous réellement rejeter cette demande ?')"
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
                        </div>
                        </div>
                    </div>
                </div>

@endsection
