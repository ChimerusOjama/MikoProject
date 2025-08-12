<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
        /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->usertype === 'admin') {
            return $next($request);
        }
        
        if (Auth::check()) {
            return redirect()->route('uAdmin')->with('error', 'Accès refusé : réservé aux administrateurs.');
        }

        return redirect()->route('login')->with('error', 'Veuillez vous connecter en tant qu\'administrateur.');
    }
}
