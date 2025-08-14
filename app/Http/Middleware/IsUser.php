<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->usertype === 'user') {
            return $next($request);
        }
        
        if (Auth::check()) {
            return redirect()->back()->with('error', 'Accès refusé : réservé aux utilisateurs.');
        }

        return redirect()->route('login')->with('error', 'Veuillez vous connecter en tant qu\'utilisateur.');
    }
}
