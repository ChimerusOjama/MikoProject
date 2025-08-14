<!-- Modal de succès -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Opération réussie</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="success-modal-body">
                <!-- Contenu dynamique injecté via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
                <a href="{{ route('uFormation') }}" class="btn btn-success">Voir mes formations</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'avertissement -->
<div class="modal fade" id="warningModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Attention requise</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="warning-modal-body">
                <!-- Contenu dynamique injecté via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Corriger</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'erreur -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-times-circle me-2"></i>Erreur</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="error-modal-body">
                <!-- Contenu dynamique injecté via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
                <a href="{{ route('contact') }}" class="btn btn-danger">Contacter le support</a>
            </div>
        </div>
    </div>
</div>