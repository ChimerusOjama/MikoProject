@extends('layouts.adApp')

@section('title', 'Lien Expiré')
@section('page-title', 'Lien de Paiement Expiré')
@section('breadcrumb')
    <li>Mon espace</li>
    <li>Formations</li>
    <li>Lien expiré</li>
@endsection

@section('forms', 'active')

@section('content')
<div class="payment-expired-container">
    <!-- Carte principale d'expiration -->
    <div class="expired-card">
        <div class="expired-header">
            <div class="expired-icon">
                <i class="fas fa-link-slash"></i>
            </div>
            <h2 class="expired-title">Lien Expiré</h2>
            <p class="expired-subtitle">Ce lien de paiement n'est plus valide</p>
        </div>

        <!-- Message d'information -->
        <div class="info-alert warning-alert">
            <i class="fas fa-exclamation-triangle"></i>
            <div class="info-alert-content">
                <h4>Inscription annulée ou expirée</h4>
                <p>Cette inscription a été annulée ou le lien de paiement a expiré. Les liens de paiement sont valables uniquement pour les inscriptions actives.</p>
            </div>
        </div>

        <!-- Explications -->
        <div class="explanations-section">
            <h3 class="explanations-title">
                <i class="fas fa-info-circle"></i> Pourquoi ce lien a expiré ?
            </h3>
            
            <div class="explanations-grid">
                <div class="explanation-item">
                    <div class="explanation-icon">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div class="explanation-content">
                        <h4>Inscription annulée</h4>
                        <p>Vous avez annulé cette inscription précédemment</p>
                    </div>
                </div>
                
                <div class="explanation-item">
                    <div class="explanation-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="explanation-content">
                        <h4>Lien expiré</h4>
                        <p>Le lien de paiement a dépassé sa durée de validité</p>
                    </div>
                </div>
                
                <div class="explanation-item">
                    <div class="explanation-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="explanation-content">
                        <h4>Déjà payé</h4>
                        <p>Cette formation a déjà été réglée avec succès</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Solutions -->
        <div class="solutions-section">
            <h3 class="solutions-title">
                <i class="fas fa-lightbulb"></i> Que faire maintenant ?
            </h3>
            
            <div class="solutions-list">
                <div class="solution-item primary">
                    <div class="solution-icon">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <div class="solution-content">
                        <h4>Nouvelle inscription</h4>
                        <p>Si vous souhaitez toujours suivre cette formation, vous pouvez créer une nouvelle inscription</p>
                        <a href="{{ route('listing') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-redo"></i> Nouvelle inscription
                        </a>
                    </div>
                </div>
                
                <div class="solution-item">
                    <div class="solution-icon">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="solution-content">
                        <h4>Vérifier vos inscriptions</h4>
                        <p>Consultez le statut de toutes vos inscriptions actuelles</p>
                        <a href="{{ route('uFormation') }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-eye"></i> Mes inscriptions
                        </a>
                    </div>
                </div>
                
                <div class="solution-item">
                    <div class="solution-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="solution-content">
                        <h4>Contacter le support</h4>
                        <p>Notre équipe peut vous aider en cas de question</p>
                        <a href="{{ route('uSupport') }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-question-circle"></i> Support
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions principales -->
        <div class="expired-actions">
            <a href="{{ route('uFormation') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-list"></i> Mes inscriptions
            </a>
            <a href="{{ route('listing') }}" class="btn btn-outline">
                <i class="fas fa-book-open"></i> Nos formations
            </a>
            <a href="{{ url('/') }}" class="btn btn-secondary">
                <i class="fas fa-home"></i> Accueil
            </a>
        </div>
    </div>
</div>
@endsection