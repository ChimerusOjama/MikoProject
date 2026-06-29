<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class EnsurePhoneIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && is_null($request->user()->phone_verified_at)) {
            
            // Si la requête est en AJAX/JSON
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Numéro de téléphone non vérifié.'], 403);
            }

            // Mémorise l'URL exacte du paiement que l'utilisateur tentait d'atteindre
            Redirect::setIntendedUrl($request->fullUrl());

            return redirect()->route('phone.verify.notice')
                ->with('warning', 'Afin de sécuriser votre paiement, veuillez vérifier votre numéro de téléphone.');
        }

        return $next($request);
    }
}