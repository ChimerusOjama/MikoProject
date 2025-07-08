@extends('layouts.adApp')

@section('title', 'Paiement Annulé')

@section('content')
<div class="payment-cancel-container">
    <div class="cancel-icon">
        <i class="fas fa-times-circle"></i>
    </div>
    <h2>Paiement Annulé</h2>
    <p>Votre tentative de paiement a été annulée. Aucun montant n'a été débité.</p>
    
    <div class="actions">
        <a href="{{ route('uFormation') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Retour à mes formations
        </a>
        <a href="{{ route('checkout', ['inscriptionId' => session('last_inscription_id')]) }}" class="btn btn-outline">
            <i class="fas fa-credit-card"></i> Réessayer le paiement
        </a>
    </div>
</div>
@endsection