@extends('layouts.adApp')

@section('title', 'Paiement Réussi')
@section('page-title', 'Confirmation de Paiement')
@section('breadcrumb')
    <li>Mon espace</li>
    <li>Formations</li>
    <li>Paiement réussi</li>
@endsection

@section('forms', 'active')

@section('content')
<div class="payment-success-container">
    <!-- Carte de confirmation principale -->
    <div class="success-card">
        <div class="success-header">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 class="success-title">Paiement Réussi !</h2>
            <p class="success-subtitle">Votre inscription a été confirmée avec succès</p>
        </div>

        <!-- Résumé de la formation -->
        <div class="info-alert success-alert">
            <i class="fas fa-graduation-cap"></i>
            <div class="info-alert-content">
                <h4>{{ $inscription->formation->titre }}</h4>
                <p>Votre accès à la formation a été activé pour une durée de 3 mois</p>
            </div>
        </div>

        <!-- Détails du paiement -->
        <div class="payment-details-card">
            <h3 class="details-title">
                <i class="fas fa-receipt"></i> Détails de la transaction
            </h3>
            
            <div class="details-grid">
                <!-- <div class="detail-item">
                    <span class="detail-label">Référence :</span>
                    <span class="detail-value">{{ $inscription->stripe_session_id ?? $inscription->id }}</span>
                </div> -->
                <div class="detail-item">
                    <span class="detail-label">Montant payé :</span>
                    <span class="detail-value highlight">15000 FCFA</span>
                    <!-- <span class="detail-value highlight">{{ number_format($inscription->montant, 0, ',', ' ') }} FCFA</span> -->
                </div>
                <div class="detail-item">
                    <span class="detail-label">Date et heure :</span>
                    <span class="detail-value">{{ now()->format('d/m/Y à H:i') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Méthode :</span>
                    <span class="detail-value">Carte bancaire</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Accès jusqu'au :</span>
                    <span class="detail-value highlight">{{ now()->addMonths(3)->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Prochaines étapes -->
        <div class="next-steps">
            <h3 class="steps-title">
                <i class="fas fa-rocket"></i> Prochaines étapes
            </h3>
            <div class="steps-list">
                <div class="step-item">
                    <div class="step-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="step-content">
                        <h4>Email de confirmation</h4>
                        <p>Un email de confirmation a été envoyé à <strong>{{ $inscription->email }}</strong></p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-icon">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div class="step-content">
                        <h4>Accéder à la formation</h4>
                        <p>Retrouvez votre formation dans votre espace "Mes formations"</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="step-content">
                        <h4>Documents et ressources</h4>
                        <p>Téléchargez les supports de cours depuis votre espace personnel</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="success-actions">
            <a href="{{ route('uFormation') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-play-circle"></i> Commencer la formation
            </a>
            <a href="{{ route('uAdmin') }}" class="btn btn-outline">
                <i class="fas fa-tachometer-alt"></i> Tableau de bord
            </a>
            <button class="btn btn-secondary" onclick="window.print()">
                <i class="fas fa-print"></i> Imprimer
            </button>
        </div>
    </div>
</div>
@endsection