@extends('layouts.sAdApp')

@section('title', 'Mise à jour form. - Tableau de Bord')
@section('page-title', 'Formation - mise à jour')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
  <li class="breadcrumb-item active" aria-current="page">Formations</li>
@endsection

@section('formation', 'active')

@section('content')
            @if(session('success'))
              <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-square">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Message</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                          </div>
                          <div class="modal-body" id="modalMessage">
                              <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                              <!-- Message injecté par JavaScript -->
                          </div>
                          <div class="modal-footer justify-content-center">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                          </div>
                      </div>
                  </div>
              </div>
          @endif
          <!-- Modal end -->
          <div class="col-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Formulaire de mise à jour</h4>
                <form class="forms-sample" action="/Mise_a_jour/formation={{ $form->id }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="form-group">
                    <label for="exampleInputName1">Libellé</label>
                    <input type="text" class="form-control" id="exampleInputName1" name="libForm" value="{{ $form->libForm }}" required placeholder="Libellé de la formation">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputName2">Description</label>
                    <input type="text" class="form-control" id="exampleInputName2" name="desc" value="{{ $form->desc }}" required placeholder="Description de la formation">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputName3">Image actuelle</label>
                    <img width="300 px" height="200 px" src="{{ $form->image }}" alt="{{ $form->image }}">
                    <!-- <img width="300 px" height="200 px" src="{{ asset('template/images/page-banner3.jpg') }}" alt="{{ $form->image }}"> -->
                  </div>
                  <div class="form-group">
                    <label for="exampleInputName3">Nouvelle image</label>
                    <input type="file" class="form-control" id="exampleInputName3" name="image">
                  </div>
                  <!-- <div class="form-group">
                    <label>Image</label>
                    <input type="file" name="image" class="file-upload-default">
                    <div class="input-group col-xs-12">
                      <input type="text" class="form-control file-upload-info" disabled placeholder="Chargez une image">
                      <span class="input-group-append">
                        <button class="file-upload-browse btn btn-primary" type="button">Ajouter</button>
                      </span>
                    </div>
                  </div> -->
                  <!-- <input type="submit" value="Soumettre"> -->
                  <button type="submit" class="btn btn-primary me-2">Mettre à jour</button>
                  <!-- <button class="btn btn-dark">Annuler</button> -->
                  <a href="{{ route('allForm') }}" class="btn btn-dark">Liste des formations</a>
                </form>
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