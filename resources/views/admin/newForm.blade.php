@extends('layouts.sAdApp')

@section('title', 'Nouvelle form. - Tableau de Bord')
@section('page-title', 'Formation - nouvelle formation')
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
                    <h4 class="card-title">Formulaire de création</h4>
                    <form class="forms-sample" action="{{ route('storeForm') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="titre">Titre</label>
                            <input type="text" class="form-control" id="titre" name="titre" required 
                                  placeholder="Titre de la formation" value="{{ old('titre') }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="description_courte">Description courte</label>
                            <textarea class="form-control" id="description_courte" name="description_courte" required 
                                      placeholder="Description résumée de la formation" rows="3">{{ old('description_courte') }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="categorie">Catégorie</label>
                            <select class="form-select" id="categorie" name="categorie" required>
                                <option value="">Sélectionnez une catégorie</option>
                                <option value="informatique" {{ old('categorie') == 'informatique' ? 'selected' : '' }}>Informatique</option>
                                <option value="gestion" {{ old('categorie') == 'gestion' ? 'selected' : '' }}>Gestion</option>
                                <option value="langues" {{ old('categorie') == 'langues' ? 'selected' : '' }}>Langues</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="niveau">Niveau</label>
                            <select class="form-select" id="niveau" name="niveau" required>
                                <option value="">Sélectionnez un niveau</option>
                                <option value="debutant" {{ old('niveau') == 'debutant' ? 'selected' : '' }}>Débutant</option>
                                <option value="intermediaire" {{ old('niveau') == 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                                <option value="avance" {{ old('niveau') == 'avance' ? 'selected' : '' }}>Avancé</option>
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="prix">Prix (FCFA)</label>
                                    <input type="number" class="form-control" id="prix" name="prix" required 
                                          min="0" max="100000" step="1000" placeholder="Ex: 150000"
                                          value="{{ old('prix') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="duree_mois">Durée (mois)</label>
                                    <input type="number" class="form-control" id="duree_mois" name="duree_mois" required 
                                          min="1" max="24" placeholder="Ex: 3"
                                          value="{{ old('duree_mois') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="stripe_price_id">Stripe ID</label>
                            <input type="text" class="form-control" id="stripe_price_id" name="stripe_price_id" required 
                                  placeholder="Ex: price_12345" value="{{ old('stripe_price_id') }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="status">Statut</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">Sélectionnez un statut</option>
                                <option value="publiee" {{ old('status') == 'publiee' ? 'selected' : '' }}>Publiée</option>
                                <option value="brouillon" {{ old('status') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                                <option value="archivee" {{ old('status') == 'archivee' ? 'selected' : '' }}>Archivée</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="image">Image</label>
                            <input type="file" class="form-control" id="image" name="image" required accept="image/jpeg,image/png,image/jpg">
                            <small class="form-text text-muted">Formats acceptés: JPG, JPEG, PNG (max 2MB)</small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary me-2">Soumettre</button>
                        <a href="{{ route('allForm') }}" class="btn btn-dark">Liste des formations</a>
                    </form>
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