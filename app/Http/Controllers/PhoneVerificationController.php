<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PhoneVerificationController extends Controller
{
    public function notice()
    {
        return view('auth.verify-phone');
    }

    public function sendOtp(Request $request)
    {
        $user = $request->user();
        
        // LOG 1 : Déclenchement de la méthode
        Log::info('[SMS Verification] Début du processus d\'envoi OTP.', [
            'user_id' => $user->id,
            'user_name' => $user->first_name . ' ' . $user->last_name,
            'raw_phone' => $user->phone
        ]);

        // 1. Formatage du numéro pour Brevo (Spécifique Congo : 242)
        $phone = preg_replace('/[^0-9]/', '', $user->phone); // Ne garde que les chiffres
        if (str_starts_with($phone, '0')) {
            $phone = substr($phone, 1); // Enlève le 0 initial si présent (06... -> 6...)
        }
        if (!str_starts_with($phone, '242')) {
            $phone = '242' . $phone; // Ajoute l'indicatif s'il manque
        }

        // LOG 2 : Fin de l'étape de formatage
        Log::info('[SMS Verification] Numéro de téléphone formaté avec succès.', [
            'user_id' => $user->id,
            'formatted_phone' => $phone
        ]);

        // 2. Génération et mise en Cache (Expire dans 5 minutes)
        $code = rand(100000, 999999);
        Cache::put('otp_' . $user->id, $code, now()->addMinutes(5));

        // LOG 3 : Stockage en Cache
        Log::info('[SMS Verification] Code OTP généré et mis en cache.', [
            'user_id' => $user->id,
            'otp_code' => $code,
            'expires_at' => now()->addMinutes(5)->toDateTimeString()
        ]);

        $apiKey = config('services.brevo.key');
        
        // LOG 4 : Vérification de la configuration locale avant appel réseau
        Log::info('[SMS Verification] État de la configuration Brevo.', [
            'has_api_key' => !empty($apiKey) ? 'OUI (Clé présente)' : 'NON (Clé manquante dans config/services.php)'
        ]);

        // 3. Appel de l'API Transactionnelle Brevo
        try {
            Log::info('[SMS Verification] Envoi de la requête HTTP POST à Brevo...', [
                'url' => 'https://api.brevo.com/v3/transactionalSMS/send',
                'recipient' => $phone
            ]);

            $response = Http::withHeaders([
                'accept' => 'application/json',
                'api-key' => $apiKey,
                'content-type' => 'application/json'
            ])->post('https://api.brevo.com/v3/transactionalSMS/send', [
                'sender' => 'Miko',
                'recipient' => $phone,
                'content' => "Code de validation Miko : {$code}. Ce code expire dans 5 minutes.",
                'type' => 'transactional'
            ]);

            // LOG 5 : Analyse brute de la réponse de l'API
            Log::info('[SMS Verification] Réponse brute reçue de l\'API Brevo.', [
                'status_code' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                Log::info('[SMS Verification] SMS envoyé avec succès et validé par Brevo.', ['user_id' => $user->id]);
                return back()->with('success', 'Un code secret a été envoyé par SMS.');
            }

            Log::error('[SMS Verification] L\'API Brevo a rejeté la demande d\'envoi.', [
                'user_id' => $user->id,
                'status' => $response->status(),
                'response' => $response->json()
            ]);
            return back()->with('error', 'Erreur de communication avec le serveur SMS.');

        } catch (\Exception $e) {
            Log::error('[SMS Verification] Exception critique lors de l\'envoi du SMS via Http Client.', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Service SMS temporairement indisponible.');
        }
    }

    public function verify(Request $request)
    {
        $user = $request->user();

        Log::info('[SMS Verification] Demande de validation d\'un code OTP reçue.', [
            'user_id' => $user ? $user->id : 'Utilisateur non connecté',
            'code_saisi' => $request->code
        ]);

        $request->validate(['code' => 'required|numeric|digits:6']);

        $cachedCode = Cache::get('otp_' . $user->id);

        Log::info('[SMS Verification] Comparaison avec le cache.', [
            'user_id' => $user->id,
            'code_en_cache' => $cachedCode
        ]);

        if (!$cachedCode) {
            Log::warning('[SMS Verification] Échec de validation : Le code en cache est introuvable ou a expiré.', [
                'user_id' => $user->id
            ]);
            return back()->with('error', 'Le code a expiré. Veuillez en demander un nouveau.');
        }

        if ($request->code == $cachedCode) {
            Log::info('[SMS Verification] Correspondance réussie. Mise à jour du statut utilisateur...', [
                'user_id' => $user->id
            ]);

            // Validation réussie
            $user->update(['phone_verified_at' => now()]);
            Cache::forget('otp_' . $user->id);
            
            Log::info('[SMS Verification] Compte utilisateur marqué comme vérifié avec succès.', [
                'user_id' => $user->id,
                'phone_verified_at' => $user->phone_verified_at
            ]);

            return redirect()->intended(route('uHome'))
                ->with('success', 'Votre compte est désormais vérifié !');
        }

        Log::warning('[SMS Verification] Échec de validation : Code saisi incorrect.', [
            'user_id' => $user->id,
            'code_saisi' => $request->code,
            'code_attendu' => $cachedCode
        ]);

        return back()->with('error', 'Code invalide. Vérifiez votre saisie.');
    }
}