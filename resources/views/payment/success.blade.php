@extends('layouts.adApp')

@section('title', 'Paiement Réussi')

@section('content')
<div class="payment-success-container">
    <div class="success-icon">
        <i class="fas fa-check-circle"></i>
    </div>
    <h2>Paiement Réussi!</h2>
    <p>Votre inscription à la formation <strong>{{ $inscription->formation->title }}</strong> a été confirmée.</p>
    
    <div class="payment-details">
        <p><strong>Référence:</strong> {{ $inscription->stripe_session_id }}</p>
        <p><strong>Montant:</strong> {{ $inscription->formation->price }} €</p>
        <p><strong>Date:</strong> {{ $inscription->payment_date->format('d/m/Y H:i') }}</p>
    </div>
    
    <div class="actions">
        <a href="{{ route('uFormation') }}" class="btn btn-primary">
            <i class="fas fa-list"></i> Retour à mes formations
        </a>
        <button class="btn btn-secondary">
            <i class="fas fa-download"></i> Télécharger la facture
        </button>
    </div>
</div>
@endsection