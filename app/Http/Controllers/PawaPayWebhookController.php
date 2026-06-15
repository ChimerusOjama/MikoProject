<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Paiement;
use App\Models\Inscription;
use App\Mail\PaymentConfirmation; // Si vous souhaitez réutiliser vos mails existants
use Illuminate\Support\Facades\Mail;

class PawaPayWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();

        // 1. Logger l'événement pour garder une trace brute dans vos fichiers de logs de paiement
        Log::channel('paiements')->info('🔔 WEBHOOK PAWAPAY REÇU :', $payload);

        // Extraction des données clés du payload type de PawaPay
        $eventType = $payload['eventType'] ?? null;
        $depositData = $payload['deposit'] ?? [];
        
        $reference = $depositData['externalId'] ?? null; // Votre référence interne
        $pawapayId = $depositData['depositId'] ?? null;  // ID de transaction PawaPay
        $status = $depositData['status'] ?? null;        // COMPLETED, FAILED, etc.

        if (!$reference) {
            Log::channel('paiements')->error('❌ Webhook PawaPay manquant de externalId (reference)');
            return response()->json(['message' => 'Missing externalId'], 400);
        }

        // 2. Trouver le paiement correspondant dans votre base de données
        $paiement = Paiement::where('reference', $reference)->first();

        if (!$paiement) {
            Log::channel('paiements')->error("❌ Aucun paiement trouvé pour la référence : {$reference}");
            return response()->json(['message' => 'Payment not found'], 404);
        }

        // Si le paiement est déjà traité, on répond 200 pour éviter que PawaPay ne renvoie le webhook
        if (in_array($paiement->statut, ['complet', 'annulé'])) {
            return response()->json(['message' => 'Webhook already processed'], 200);
        }

        // 3. Traitement selon le statut renvoyé par PawaPay
        try {
            DB::beginTransaction();

            if ($eventType === 'DEPOSIT_DELIVERED' || $status === 'COMPLETED') {
                // Mise à jour du Paiement
                $paiement->update([
                    'statut' => 'complet',
                    'stripe_payment_id' => $pawapayId, // On peut réutiliser ce champ ou en créer un spécifique 'pawapay_payment_id'
                    'date_paiement' => now(),
                ]);

                // Mise à jour de l'Inscription liée
                $inscription = $paiement->inscription;
                if ($inscription) {
                    // Si c'est un paiement complet ou le solde restant d'un paiement partiel
                    $inscription->update([
                        'statut_paiement' => 'complet',
                        'status' => 'Accepté',
                        'payment_date' => now(),
                    ]);

                    // Optionnel : Envoi de l'email de confirmation automatique
                    try {
                        Mail::to($inscription->email)->send(new PaymentConfirmation($inscription));
                    } catch (\Exception $e) {
                        Log::channel('paiements')->error("❌ Erreur envoi email suite webhook PawaPay : " . $e->getMessage());
                    }
                }

                Log::channel('paiements')->info("✅ Paiement validé avec succès via Webhook pour la référence : {$reference}");

            } elseif ($eventType === 'DEPOSIT_FAILED' || $status === 'FAILED') {
                // Le paiement a échoué côté opérateur mobile money
                $paiement->update([
                    'statut' => 'annulé',
                ]);

                Log::channel('paiements')->warning("⚠️ Paiement échoué/annulé via Webhook pour la référence : {$reference}");
            }

            DB::commit();
            return response()->json(['message' => 'Webhook processed successfully'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('paiements')->error("❌ Échec critique lors du traitement du Webhook : " . $e->getMessage());
            return response()->json(['message' => 'Server error processing webhook'], 500);
        }
    }
}