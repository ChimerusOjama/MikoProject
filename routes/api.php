<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

use App\Models\Paiement;

// La route invisible appelée par le script Javascript (Polling)
Route::get('/payment/check-status/{reference}', function ($reference) {
    $paiement = Paiement::where('reference', $reference)->first();
    
    if (!$paiement) {
        return response()->json(['statut' => 'introuvable'], 404);
    }
    
    return response()->json([
        'statut' => $paiement->statut,
        'inscription_id' => $paiement->inscription_id
    ]);
});
