@extends('layouts.adApp')

@section('title', 'Paiement Annulé')
@section('page-title', 'Paiement Interrompu')
@section('breadcrumb')
    <li>Mon espace</li>
    <li>Formations</li>
    <li>Paiement annulé</li>
@endsection

@section('forms', 'active')

@section('content')
<div class="payment-cancel-container">
    <!-- Carte principale d'annulation -->
    <div class="cancel-card">
        <div class="cancel-header">
            <div class="cancel-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <h2 class="cancel-title">Paiement Annulé</h2>
            <p class="cancel-subtitle">Le processus de paiement a été interrompu</p>
        </div>

        <!-- Message d'information -->
        <div class="info-alert warning-alert">
            <i class="fas fa-exclamation-triangle"></i>
            <div class="info-alert-content">
                <h4>Transaction non finalisée</h4>
                <p>Votre inscription n'a pas été confirmée. Vous pouvez réessayer le paiement à tout moment.</p>
            </div>
        </div>

        <!-- Statut de l'inscription -->
        <div class="status-card">
            <h3 class="status-title">
                <i class="fas fa-info-circle"></i> Statut actuel
            </h3>
            
            <div class="status-content">
                <div class="status-item">
                    <div class="status-icon pending">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="status-details">
                        <h4>Inscription en attente de paiement</h4>
                        <p>Votre place est réservée temporairement</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Raisons possibles -->
        <div class="reasons-section">
            <h3 class="reasons-title">
                <i class="fas fa-question-circle"></i> Pourquoi le paiement a-t-il été annulé ?
            </h3>
            
            <div class="reasons-grid">
                <div class="reason-item">
                    <div class="reason-icon">
                        <i class="fas fa-undo"></i>
                    </div>
                    <div class="reason-content">
                        <h4>Annulation volontaire</h4>
                        <p>Vous avez choisi d'interrompre le processus de paiement</p>
                    </div>
                </div>
                
                <div class="reason-item">
                    <div class="reason-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="reason-content">
                        <h4>Délai dépassé</h4>
                        <p>La session de paiement a expiré par mesure de sécurité</p>
                    </div>
                </div>
                
                <div class="reason-item">
                    <div class="reason-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="reason-content">
                        <h4>Problème technique</h4>
                        <p>Une erreur temporaire peut avoir interrompu la transaction</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions recommandées -->
        <div class="recommendations">
            <h3 class="recommendations-title">
                <i class="fas fa-lightbulb"></i> Que faire maintenant ?
            </h3>
            
            <div class="recommendations-list">
                <div class="recommendation-item primary">
                    <div class="recommendation-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="recommendation-content">
                        <h4>Réessayer le paiement</h4>
                        <p>Retournez à vos formations pour relancer le processus de paiement</p>
                        <a href="{{ route('uFormation') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-right"></i> Payer maintenant
                        </a>
                    </div>
                </div>
                
                <div class="recommendation-item">
                    <div class="recommendation-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <div class="recommendation-content">
                        <h4>Obtenir de l'aide</h4>
                        <p>Contactez notre support en cas de problème récurrent</p>
                        <a href="{{ route('uSupport') }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-headset"></i> Contacter le support
                        </a>
                    </div>
                </div>
                
                <div class="recommendation-item">
                    <div class="recommendation-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="recommendation-content">
                        <h4>Vérifier vos informations</h4>
                        <p>Assurez-vous que vos moyens de paiement sont valides</p>
                        <a href="{{ route('uProfile') }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-user"></i> Mon profil
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions principales -->
        <div class="cancel-actions">
            <a href="{{ route('uFormation') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-credit-card"></i> Réessayer le paiement
            </a>
            <a href="{{ route('uAdmin') }}" class="btn btn-outline">
                <i class="fas fa-tachometer-alt"></i> Tableau de bord
            </a>
            <a href="{{ url('/') }}" class="btn btn-secondary">
                <i class="fas fa-home"></i> Page d'accueil
            </a>
        </div>

        <!-- Informations de support -->
        <div class="support-info">
            <div class="support-content">
                <i class="fas fa-headset"></i>
                <div>
                    <h4>Besoin d'aide ?</h4>
                    <p>Notre équipe support est disponible pour vous accompagner</p>
                    <div class="support-contacts">
                        <span><i class="fas fa-envelope"></i> support@mikoformation.cg</span>
                        <span><i class="fas fa-phone"></i> +242 XX XX XX XX</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection