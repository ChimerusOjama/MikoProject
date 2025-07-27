@extends('layouts.sAdApp')

@section('title', 'Tableau de Bord')
@section('page-title', 'Tableau de Bord')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
  <li class="breadcrumb-item active" aria-current="page">Acceuil</li>
@endsection

@section('dashboard', 'active')

@section('content')
  <div class="row">
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
          <h4 class="card-title">Suivi des Inscriptions</h4>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>N°</th>
                  <th>Client</th>
                  <th>Formation</th>
                  <th>Montant</th>
                  <th>Date de paiement</th>
                  <th>Mode de paiement</th>
                  <th>Statut</th>
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
                </tr>
                <tr>
                  <td>#0002</td>
                  <td>Estella Bryan</td>
                  <td>Formation JavaScript</td>
                  <td>15 000 FCFA</td>
                  <td>Aucun</td>
                  <td>Aucun</td>
                  <td><span class="badge badge-outline-warning">En attente</span></td>
                </tr>
                <tr>
                  <td>#0003</td>
                  <td>Peter Gill</td>
                  <td>Pack complet Web</td>
                  <td>30 000 FCFA</td>
                  <td>Aucun</td>
                  <td>Aucun</td>
                  <td><span class="badge badge-outline-danger">Rejeté</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection