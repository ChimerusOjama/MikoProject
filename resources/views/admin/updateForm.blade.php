@extends('layouts.sAdApp')

@section('title', 'Mise à jour form. - Tableau de Bord')
@section('page-title', 'Formation - mise à jour')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
  <li class="breadcrumb-item active" aria-current="page">Formations</li>
@endsection

@section('formation', 'active')

@section('content')
@include('components.alert-adModal')
          <div class="col-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Formulaire de mise à jour</h4>
                <form class="forms-sample" action="/Mise_a_jour/formation={{ $form->id }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="form-group">
                    <label for="titre">Titre</label>
                    <input type="text" class="form-control" id="titre" name="titre" value="{{ $form->titre }}" required placeholder="Titre de la formation">
                  </div>
                  <div class="form-group">
                    <label for="description_courte">Description courte</label>
                    <textarea class="form-control" id="description_courte" name="description_courte" required placeholder="Description résumée de la formation" rows="3">{{ $form->description_courte }}</textarea>
                  </div>
                  <div class="form-group">
                    <label for="categorie">Catégorie</label>
                    <select class="form-control" id="categorie" name="categorie" required>
                      <option value="informatique" {{ $form->categorie == 'informatique' ? 'selected' : '' }}>Informatique</option>
                      <option value="gestion" {{ $form->categorie == 'gestion' ? 'selected' : '' }}>Gestion</option>
                      <option value="langues" {{ $form->categorie == 'langues' ? 'selected' : '' }}>Langues</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="niveau">Niveau</label>
                    <select class="form-control" id="niveau" name="niveau" required>
                      <option value="debutant" {{ $form->niveau == 'debutant' ? 'selected' : '' }}>Débutant</option>
                      <option value="intermediaire" {{ $form->niveau == 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                      <option value="avance" {{ $form->niveau == 'avance' ? 'selected' : '' }}>Avancé</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="prix">Prix</label>
        
                    <input type="number" class="form-control" id="prix" name="prix" value="{{ $form->prix }}" required min="0" max="100000" step="1000" placeholder="Prix de la formation">
                  </div>
                  <div class="form-group">
                    <label for="duree_mois">Durée (mois)</label>
  
                    <input type="number" class="form-control" id="duree_mois" name="duree_mois" value="{{ $form->duree_mois }}" required min="1" max="24" placeholder="Durée en mois">
                  </div>
                  <div class="form-group">
                      <label for="stripe_price_id">Stripe ID</label>
                      <input type="text" class="form-control" id="stripe_price_id" name="stripe_price_id" required 
                            placeholder="Ex: price_12345" value="{{ old('stripe_price_id') }}">
                  </div>
                  <div class="form-group">
                    <label for="status">Statut</label>
                    <select class="form-control" id="status" name="status" required>
                      <option value="publiee" {{ $form->status == 'publiee' ? 'selected' : '' }}>Publiée</option>
                      <option value="brouillon" {{ $form->status == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                      <option value="archivee" {{ $form->status == 'archivee' ? 'selected' : '' }}>Archivée</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Image actuelle</label><br>
                    <img width="300" height="200" src="{{ asset($form->image_url) }}" alt="Image formation">
                  </div>
                  <div class="form-group">
                    <label for="image">Nouvelle image</label>
                    <input type="file" class="form-control" id="image" name="image">
                  </div>
                  <button type="submit" class="btn btn-primary me-2">Mettre à jour</button>
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