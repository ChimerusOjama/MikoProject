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
    <div class="info-alert">
        <i class="fas fa-info-circle"></i>
        <div class="info-alert-content">
            <h4>{{ $inscription->formation->titre }}</h4>
            <p>
                <strong>Montant :</strong> 
                <span class="text-primary">{{ number_format($inscription->montant, 0, ',', ' ') }} FCFA</span> | 
                <strong>Référence :</strong> #{{ $inscription->id }}
            </p>
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
                            data-method="{{ $methode['id'] }}"
                            title="Cliquez ici pour choisir {{ $methode['nom'] }} et procéder au paiement">
                    @else
                    <div class="payment-method-btn disabled">
                    @endif
                    
                        @if(!$methode['disponible'])
                        <span class="method-badge method-badge-coming">
                            <i class="fas fa-clock"></i> Bientôt
                        </span>
                        @endif
                        
                        <div class="method-icon">
                            <i class="fas {{ $methode['icone'] }} {{ $methode['disponible'] ? 'icon-available' : 'icon-disabled' }}"></i>
                        </div>
                        
                        <div class="method-content">
                            <h4 class="method-title {{ $methode['disponible'] ? 'title-available' : 'title-disabled' }}">
                                {{ $methode['nom'] }}
                            </h4>
                            <p class="method-description {{ $methode['disponible'] ? 'description-available' : 'description-disabled' }}">
                                {{ $methode['description'] }}
                            </p>
                            
                            @if(isset($methode['frais']) && $methode['frais'] > 0)
                            <div class="method-fees">
                                <i class="fas fa-info-circle"></i>
                                Frais : +{{ number_format($methode['frais'], 0, ',', ' ') }} FCFA
                            </div>
                            @endif
                            
                            @if(isset($methode['message']))
                            <div class="method-message">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ $methode['message'] }}
                            </div>
                            @endif
                            
                            @if($methode['disponible'])
                            <div class="method-action">
                                <span class="action-text">Cliquer pour payer</span>
                                <i class="fas fa-arrow-right action-arrow"></i>
                            </div>
                            @endif
                        </div>
                    
                    @if($methode['disponible'])
                    </button>
                    @else
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Actions secondaires -->
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
    
    paymentButtons.forEach(button => {
        button.addEventListener('click', function() {
            const method = this.getAttribute('data-method');
            
            // Ajouter un effet visuel de sélection
            paymentButtons.forEach(btn => {
                btn.classList.remove('active');
                btn.querySelector('.action-arrow').style.transform = 'translateX(0)';
            });
            this.classList.add('active');
            this.querySelector('.action-arrow').style.transform = 'translateX(5px)';
            
            // Soumettre le formulaire après un court délai pour l'effet visuel
            setTimeout(() => {
                selectedMethodInput.value = method;
                paymentForm.submit();
            }, 300);
        });
        
        // Effet hover
        button.addEventListener('mouseenter', function() {
            if (!this.classList.contains('active')) {
                this.querySelector('.action-arrow').style.transform = 'translateX(3px)';
            }
        });
        
        button.addEventListener('mouseleave', function() {
            if (!this.classList.contains('active')) {
                this.querySelector('.action-arrow').style.transform = 'translateX(0)';
            }
        });
    });
});
</script>
@endsection