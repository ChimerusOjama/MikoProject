@extends('layouts.app')

@section('title', 'Vérification du téléphone - Miko Formation')

@section('meta')
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('content')

{{-- On appelle le composant HTML exactement comme dans la page d'inscription --}}
<x-alert-modal />

<div class="auth-page verification-page py-5">
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 60vh;">
        <div class="col-md-6 col-lg-5">
            <div class="verification-card card p-4 p-md-5 shadow-lg border-0" style="border-radius: 15px;">
                
                <div class="verification-card-logo text-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" style="height: 60px; width: 60px;">
                        <text y=".9em" font-size="90">🔒</text>
                    </svg>
                </div>
                
                <h2 class="verification-title text-center text-primary fw-bold mb-3">Vérification de sécurité</h2>
                
                <p class="verification-description text-center text-muted mb-4">
                    Pour sécuriser vos paiements sur Miko, veuillez valider le numéro de téléphone associé à votre compte : <br>
                    <strong class="text-dark fs-5">{{ auth()->user()->phone }}</strong>
                </p>
                
                <!-- Formulaire d'envoi du code -->
                <form action="{{ route('phone.send') }}" method="POST" class="mb-4 text-center">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary w-100 py-2 rounded-pill fw-bold">
                        <i class="fas fa-sms me-2"></i> Envoyer le code par SMS
                    </button>
                </form>

                <hr class="my-4 text-muted">

                <!-- Formulaire de saisie et validation du code OTP -->
                <form action="{{ route('phone.verify') }}" method="POST">
                    @csrf
                    <div class="form-group mb-4">
                        <label for="code" class="form-label fw-bold text-dark">Entrez le code à 6 chiffres</label>
                        <input type="text" class="form-control form-control-lg text-center fw-bold fs-4 tracking-widest" 
                               id="code" name="code" placeholder="000000" maxlength="6" required 
                               style="letter-spacing: 0.5rem;">
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold">
                        Valider mon compte <i class="fas fa-check-circle ms-2"></i>
                    </button>
                </form>
                
            </div>
        </div>
    </div>
</div>
@endsection