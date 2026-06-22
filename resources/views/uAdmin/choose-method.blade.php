@extends('layouts.adApp')

@section('title', 'Choisir le moyen de paiement')
@section('page-title', 'Moyen de paiement')
@section('breadcrumb')
    <li>Mon espace</li>
    <li>Formations</li>
    <li>Paiement</li>
@endsection

@section('forms', 'active')

@section('content')
<div class="payment-methods-container">
    <!-- Résumé de la commande -->
    {{-- <div class="info-alert">
        <i class="fas fa-info-circle"></i>
        <div class="info-alert-content">
            <h4>{{ $inscription->formation->titre }}</h4>
            <p>
                <strong>Montant :</strong> 
                <span class="text-primary">{{ number_format($inscription->formation->prix, 0, ',', ' ') }} FCFA</span> | 
                <strong>Référence :</strong> #{{ $inscription->id }}
            </p>
        </div>
    </div> --}}

    <div class="info-alert">
        <i class="fas fa-info-circle"></i>
        <div class="info-alert-content" style="width: 100%;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h4>{{ $inscription->formation->titre }}</h4>
                    <p style="margin-bottom: 0;"><strong>Référence :</strong> #{{ $inscription->id }}</p>
                </div>
                <div style="text-align: right; background: rgba(255,255,255,0.6); padding: 10px 15px; border-radius: 8px;">
                    <div style="font-size: 13px; color: var(--text-medium);">Prix total : {{ number_format($prixTotal, 0, ',', ' ') }} FCFA</div>
                    
                    @if($montantDejaPaye > 0)
                    <div style="font-size: 13px; color: var(--success);">Déjà payé : {{ number_format($montantDejaPaye, 0, ',', ' ') }} FCFA</div>
                    @endif
                    
                    <div style="font-size: 16px; font-weight: bold; color: var(--primary); margin-top: 5px;">
                        Reste à payer : {{ number_format($resteAPayer, 0, ',', ' ') }} FCFA
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <h3 class="section-title"><i class="fas fa-credit-card"></i> Choisissez votre moyen de paiement</h3>

        <form action="{{ route('payment.process', $inscription->id) }}" method="POST" class="payment-form" id="paymentForm">
            @csrf
            <input type="hidden" name="methode_paiement" id="selectedMethod">
            
            <div class="payment-methods-grid">
                @foreach($methodesPaiement as $methode)
                <div class="payment-method-card {{ !$methode['disponible'] ? 'method-disabled' : '' }}">
                    @if($methode['disponible'])
                    <button type="button" 
                            class="payment-method-btn"
                            data-method="{{ $methode['id'] }}">
                    @else
                    <div class="payment-method-btn disabled">
                    @endif
                        
                        <div class="method-icon"><i class="fas {{ $methode['icone'] }}"></i></div>
                        <div class="method-content">
                            <h4 class="method-title">{{ $methode['nom'] }}</h4>
                            <p class="method-description">{{ $methode['description'] }}</p>
                        </div>
                        
                    @if($methode['disponible'])
                    </button>
                    @else
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            <div id="phone-input-section" style="display: none; background: rgba(63, 81, 181, 0.05); padding: 25px; border-radius: 10px; border: 1px solid var(--primary); margin-bottom: 25px;">
                <h4 style="margin-bottom: 20px; color: var(--text-dark); font-family: 'Poppins'; text-align: center;">Détails du paiement Mobile Money</h4>
                
                <div style="display: flex; gap: 20px; max-width: 600px; margin: 0 auto; flex-wrap: wrap;">
                    
                    <div class="form-group" style="flex: 1; min-width: 200px; text-align: left;">
                        <label for="montant" style="font-weight: 500; font-size: 14px;">Montant à payer (FCFA)</label>
                        <input type="number" name="montant" id="montant" class="form-control" 
                               value="{{ $resteAPayer }}" 
                               min="{{ $resteAPayer >= 5000 ? 5000 : $resteAPayer }}" 
                               max="{{ $resteAPayer }}" 
                               style="font-size: 1.1rem; font-weight: bold; color: var(--primary);">
                        <small style="color: var(--text-medium); font-size: 12px;">Minimum requis : {{ $resteAPayer >= 5000 ? '5 000' : number_format($resteAPayer, 0, ',', ' ') }} FCFA</small>
                    </div>

                    <div class="form-group" style="flex: 1; min-width: 200px; text-align: left;">
                        <label for="phone_number" style="font-weight: 500; font-size: 14px;">Numéro de téléphone</label>
                        <input type="tel" name="phone_number" id="phone_number" class="form-control" 
                               placeholder="Ex: 061234567" 
                               style="font-size: 1.1rem; letter-spacing: 1px;">
                    </div>
                    
                </div>

                <div style="text-align: center; margin-top: 20px;">
                    <button type="submit" class="btn btn-primary btn-lg" id="btn-submit-payment">
                        <i class="fas fa-lock"></i> Valider le paiement
                    </button>
                </div>
            </div>

            <div class="payment-secondary-actions">
                <a href="{{ route('uFormation') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Retour aux formations
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentButtons = document.querySelectorAll('.payment-method-btn:not(.disabled)');
    const paymentForm = document.getElementById('paymentForm');
    const selectedMethodInput = document.getElementById('selectedMethod');
    
    const phoneSection = document.getElementById('phone-input-section');
    const phoneInput = document.getElementById('phone_number');
    const montantInput = document.getElementById('montant');
    
    paymentButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const method = this.getAttribute('data-method');
            selectedMethodInput.value = method;
            
            // Effets visuels de sélection
            paymentButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Logique d'affichage
            if(method === 'momo' || method === 'airtel_money') {
                phoneSection.style.display = 'block';
                phoneInput.setAttribute('required', 'required');
                montantInput.setAttribute('required', 'required');
                phoneInput.focus();
            } else {
                phoneSection.style.display = 'none';
                phoneInput.removeAttribute('required');
                montantInput.removeAttribute('required');
                setTimeout(() => {
                    paymentForm.submit();
                }, 300);
            }
        });
    });

    // Validation Javascript avant soumission pour être sûr
    paymentForm.addEventListener('submit', function(e) {
        if(selectedMethodInput.value === 'momo' || selectedMethodInput.value === 'airtel_money') {
            const montant = parseInt(montantInput.value);
            const min = parseInt(montantInput.getAttribute('min'));
            const max = parseInt(montantInput.getAttribute('max'));

            if (montant < min || montant > max) {
                e.preventDefault();
                alert(`Le montant doit être compris entre ${min} et ${max} FCFA.`);
                montantInput.style.borderColor = 'var(--danger)';
            }
        }
    });
});
</script>

@endsection