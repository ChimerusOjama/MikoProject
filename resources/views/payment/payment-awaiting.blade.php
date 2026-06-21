@extends('layouts.adApp')

@section('title', 'En attente de confirmation')
@section('page-title', 'Validation en cours')

@section('content')
<div class="payment-cancel-container" style="text-align: center; max-width: 600px;">
    <div class="cancel-card" style="padding: 40px 30px;">
        
        <div class="loader" style="margin: 0 auto 30px; border-top-color: var(--primary); display: block; position: relative;"></div>
        
        <h2 style="font-family: 'Poppins'; color: var(--text-dark); margin-bottom: 15px;">Vérifiez votre téléphone</h2>
        
        <div class="info-alert" style="background: rgba(63, 81, 181, 0.05); border-left-color: var(--primary); text-align: left;">
            <i class="fas fa-mobile-alt" style="color: var(--primary);"></i>
            <div class="info-alert-content">
                <p style="margin: 0;">Un message s'est affiché sur votre téléphone. Veuillez <strong>saisir votre code PIN</strong> pour valider le paiement.</p>
            </div>
        </div>

        <p style="color: var(--text-medium); font-size: 14px; margin-bottom: 30px;">
            Ne fermez pas cette page. Elle vous redirigera automatiquement dès que le paiement sera confirmé par votre opérateur.
        </p>

        <a href="{{ route('payment.cancel') }}" class="btn btn-outline btn-sm">
            Annuler la transaction
        </a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reference = "{{ $reference }}";
        
        // Polling : Interrogation du serveur toutes les 3 secondes
        const interval = setInterval(() => {
            fetch(`/api/payment/check-status/${reference}`)
                .then(response => response.json())
                .then(data => {
                    if(data.statut === 'complet') {
                        clearInterval(interval);
                        // Redirection vers ta magnifique page success.blade.php
                        window.location.href = `/user/payment/success/${data.inscription_id}`; 
                    } else if (data.statut === 'annulé') {
                        clearInterval(interval);
                        // Redirection vers ta page cancel.blade.php
                        window.location.href = "{{ route('payment.cancel') }}";
                    }
                });
        }, 3000);
    });
</script>
@endsection