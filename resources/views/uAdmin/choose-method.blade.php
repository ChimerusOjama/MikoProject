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
                <span class="text-primary">{{ number_format($inscription->formation->prix, 0, ',', ' ') }} FCFA</span> | 
                <strong>Référence :</strong> #{{ $inscription->id }}
            </p>
        </div>
    </div>

    <div class="section">
        <h3 class="section-title"><i class="fas fa-credit-card"></i> Choisissez votre moyen de paiement</h3>
        
        {{-- <form action="{{ route('payment.process', $inscription->id) }}" method="POST" class="payment-form" id="paymentForm">
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
        </form> --}}

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

            <div id="phone-input-section" style="display: none; background: rgba(63, 81, 181, 0.05); padding: 25px; border-radius: 10px; border: 1px solid var(--primary); margin-bottom: 25px; text-align: center;">
                <h4 style="margin-bottom: 15px; color: var(--text-dark); font-family: 'Poppins';">Numéro Mobile Money</h4>
                <div class="form-group" style="max-width: 350px; margin: 0 auto;">
                    <input type="tel" name="phone_number" id="phone_number" class="form-control" placeholder="Ex: 061234567" style="text-align: center; font-size: 1.2rem; letter-spacing: 2px;">
                </div>
                <button type="submit" class="btn btn-primary btn-lg" id="btn-submit-payment" style="margin-top: 20px;">
                    <i class="fas fa-lock"></i> Valider le paiement
                </button>
            </div>

            <div class="payment-secondary-actions">
                <a href="{{ route('uFormation') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Retour aux formations
                </a>
            </div>
        </form>
    </div>
</div>

{{-- <script>
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
</script> --}}

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentButtons = document.querySelectorAll('.payment-method-btn:not(.disabled)');
    const paymentForm = document.getElementById('paymentForm');
    const selectedMethodInput = document.getElementById('selectedMethod');
    const phoneSection = document.getElementById('phone-input-section');
    const phoneInput = document.getElementById('phone_number');
    
    paymentButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Empêche le comportement par défaut
            
            const method = this.getAttribute('data-method');
            selectedMethodInput.value = method;
            
            // Effets visuels de sélection
            paymentButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Logique décisionnelle
            if(method === 'momo' || method === 'airtel_money') {
                // Si Mobile Money : On affiche le champ téléphone et on arrête la soumission automatique
                phoneSection.style.display = 'block';
                phoneInput.setAttribute('required', 'required'); // Rend le champ obligatoire
                phoneInput.focus();
            } else {
                // Si Stripe : On cache le champ téléphone et on soumet le formulaire directement
                phoneSection.style.display = 'none';
                phoneInput.removeAttribute('required');
                setTimeout(() => {
                    paymentForm.submit();
                }, 300);
            }
        });
    });
});
</script>

@endsection