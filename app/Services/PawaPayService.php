<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PawaPayService
{
    protected string $baseUrl;
    protected string $token;

    public function __construct()
    {
        $this->baseUrl = config('services.pawapay.base_url');
        $this->token = config('services.pawapay.token');
    }

    /**
     * Initie une demande de prélèvement Mobile Money (Prompt USSD PIN)
     */
    public function initiateDeposit(string $depositId, string $phone, int $amount, string $description)
    {
        $formattedPhone = $this->formatPhoneNumber($phone);
        $operator = $this->detectOperator($formattedPhone);

        // On crée un relevé propre : sans accents, sans caractères spéciaux et max 15 caractères
        // Exemple : "Inscription Miko" devient "Inscription Miko" -> coupé à "Inscription Mik"
        $statementDescription = substr(preg_replace('/[^A-Za-z0-9 ]/', '', $description), 0, 15);

        try {
            // Envoi de la requête complète avec toutes les exigences de PawaPay
            $response = Http::withToken($this->token)
                ->post($this->baseUrl . '/deposits', [
                    'depositId' => $depositId,
                    'amount' => (string) $amount,
                    'currency' => 'XAF',
                    'correspondent' => $operator,
                    'description' => $description,
                    'statementDescription' => $statementDescription, // <-- AJOUTÉ ET SÉCURISÉ
                    'customerTimestamp' => now()->toIso8601String(),
                    'payer' => [
                        'type' => 'MSISDN',
                        'address' => [
                            'value' => $formattedPhone
                        ]
                    ]
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PawaPay Deposit Failed: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('PawaPay Service Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Nettoie et formate le numéro au format international (Ex: 242068552497)
     */
    private function formatPhoneNumber(string $phone): string
    {
        $cleaned = preg_replace('/[^0-9]/', '', $phone);
        
        if (str_starts_with($cleaned, '06') || str_starts_with($cleaned, '05')) {
            return '242' . $cleaned;
        }
        
        return $cleaned;
    }

    /**
     * Détermine l'opérateur en fonction du numéro formaté
     */
    private function detectOperator(string $phone): string
    {
        if (str_starts_with($phone, '24206')) {
            return 'MTN_CG'; 
        }
        
        return 'AIRTEL_CG';
    }
}