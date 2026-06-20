<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PawaPayService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Paiement;

class PawaPayController extends Controller
{
    protected PawaPayService $pawaPayService;

    public function __construct(PawaPayService $pawaPayService)
    {
        $this->pawaPayService = $pawaPayService;
    }

    /**
     * Traite la réception du Webhook / Callback pour les Dépôts
     */
    public function handleDepositCallback(Request $request)
    {
        Log::info('PawaPay Callback Received:', $request->all());

        // Récupération des données normalisées de PawaPay
        $depositId = $request->input('depositId');
        $status = $request->input('status'); // Ex: 'COMPLETED', 'FAILED'
        $failureCode = $request->input('failureCode');

        // Recherche de la transaction dans ta table paiements via sa référence unique
        $paiement = DB::table('paiements')->where('reference', $depositId)->first();

        if (!$paiement) {
            Log::warning("PawaPay Callback: Paiement introuvable pour la référence : " . $depositId);
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        if ($status === 'COMPLETED') {
            DB::table('paiements')
                ->where('reference', $depositId)
                ->update([
                    'statut' => 'complet',
                    'pawapay_payment_id' => $request->input('pawaPayOrderId'), // ID de leur système
                    'date_paiement' => now(),
                    'updated_at' => now(),
                ]);
                
            Log::info("PawaPay: Transaction validée avec succès pour la référence " . $depositId);
            
            // TODO : Optionnel - Déclencher ici l'envoi d'un e-mail de confirmation à l'apprenant
            
        } elseif ($status === 'FAILED') {
            DB::table('paiements')
                ->where('reference', $depositId)
                ->update([
                    'statut' => 'annulé',
                    'failure_code' => $failureCode,
                    'updated_at' => now(),
                ]);
            Log::warning("PawaPay: Transaction échouée pour la référence " . $depositId . ". Motif : " . $failureCode);
        }

        // Toujours renvoyer un statut HTTP 200 à PawaPay pour accuser bonne réception du webhook
        return response()->json(['status' => 'OK'], 200);
    }

    /**
     * Callback pour les Payouts (Retraits / Optionnel)
     */
    public function handlePayoutCallback(Request $request)
    {
        Log::info('PawaPay Payout Callback Received:', $request->all());
        return response()->json(['status' => 'OK'], 200);
    }

    /**
     * Callback pour les Remboursements (Optionnel)
     */
    public function handleRefundCallback(Request $request)
    {
        Log::info('PawaPay Refund Callback Received:', $request->all());
        return response()->json(['status' => 'OK'], 200);
    }
}