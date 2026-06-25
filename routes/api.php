<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Paiement;
use App\Http\Controllers\PawaPayController;

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



// Ces routes seront automatiquement préfixées par /api/ par Laravel
Route::post('/pawapay/callback-deposit', [PawaPayController::class, 'handleDepositCallback'])->name('api.pawapay.deposit');
Route::post('/pawapay/callback-payout', [PawaPayController::class, 'handlePayoutCallback'])->name('api.pawapay.payout');
Route::post('/pawapay/callback-refund', [PawaPayController::class, 'handleRefundCallback'])->name('api.pawapay.refund');

// La route invisible appelée par le script Javascript (Polling)
// Route::get('/payment/check-status/{reference}', function ($reference) {
//     $paiement = Paiement::where('reference', $reference)->first();
    
//     if (!$paiement) {
//         return response()->json(['statut' => 'introuvable'], 404);
//     }
    
//     return response()->json([
//         'statut' => $paiement->statut,
//         'inscription_id' => $paiement->inscription_id
//     ]);
// });
