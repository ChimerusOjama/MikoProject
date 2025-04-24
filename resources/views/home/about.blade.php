@include('layouts.partials.headPage')
<!-- Bouton de déclenchement -->
<div class="text-center mt-5">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Open Modal
    </button>
</div>

<!-- Modal -->
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-square">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Message</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body" id="modalMessage">
                        <!-- Message injecté par JavaScript -->
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal end -->

    <div class="row">
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <img src="{{ asset('template/images/course-pic.jpg') }}" class="card-img-top" alt="Cours">
                <div class="card-body">
                    <h5 class="card-title">ttt</h5>
                    <p class="tCard card-text">Durée : 3 mois</p>
                    <p class="tCard card-text">Horaires : 8h00-10h00 / 13h00-15h00</p>
                    <p class="tCard card-text"><strong>Prix : 14 500 FCFA</strong></p>
                    <a href="#" class="btn btn-primary w-100">Voir le cours</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <img src="{{ asset('template/images/course-pic.jpg') }}" class="card-img-top" alt="Cours">
                <div class="card-body">
                    <h5 class="card-title">ttt</h5>
                    <p class="tCard card-text">Durée : 3 mois</p>
                    <p class="tCard card-text">Horaires : 8h00-10h00 / 13h00-15h00</p>
                    <p class="tCard card-text"><strong>Prix : 14 500 FCFA</strong></p>
                    <a href="#" class="btn btn-primary w-100">Voir le cours</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <img src="{{ asset('template/images/course-pic.jpg') }}" class="card-img-top" alt="Cours">
                <div class="card-body">
                    <h5 class="card-title">ttt</h5>
                    <p class="tCard card-text">Durée : 3 mois</p>
                    <p class="tCard card-text">Horaires : 8h00-10h00 / 13h00-15h00</p>
                    <p class="tCard card-text"><strong>Prix : 14 500 FCFA</strong></p>
                    <a href="#" class="btn btn-primary w-100">Voir le cours</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <img src="{{ asset('template/images/course-pic.jpg') }}" class="card-img-top" alt="Cours">
                <div class="card-body">
                    <h5 class="card-title">ttt</h5>
                    <p class="tCard card-text">Durée : 3 mois</p>
                    <p class="tCard card-text">Horaires : 8h00-10h00 / 13h00-15h00</p>
                    <p class="tCard card-text"><strong>Prix : 14 500 FCFA</strong></p>
                    <a href="#" class="btn btn-primary w-100">Voir le cours</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <img src="{{ asset('template/images/course-pic.jpg') }}" class="card-img-top" alt="Cours">
                <div class="card-body">
                    <h5 class="card-title">ttt</h5>
                    <p class="tCard card-text">Durée : 3 mois</p>
                    <p class="tCard card-text">Horaires : 8h00-10h00 / 13h00-15h00</p>
                    <p class="tCard card-text"><strong>Prix : 14 500 FCFA</strong></p>
                    <a href="#" class="btn btn-primary w-100">Voir le cours</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <img src="{{ asset('template/images/course-pic.jpg') }}" class="card-img-top" alt="Cours">
                <div class="card-body">
                    <h5 class="card-title">ttt</h5>
                    <p class="tCard card-text">Durée : 3 mois</p>
                    <p class="tCard card-text">Horaires : 8h00-10h00 / 13h00-15h00</p>
                    <p class="tCard card-text"><strong>Prix : 14 500 FCFA</strong></p>
                    <a href="#" class="btn btn-primary w-100">Voir le cours</a>
                </div>
            </div>
        </div>
    </div>

@include('layouts.partials.footPage')
