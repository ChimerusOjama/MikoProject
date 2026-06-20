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

        try {
            // Envoi de la requête avec l'imbrication complète et correcte
            $response = Http::withToken($this->token)
                ->post($this->baseUrl . '/deposits', [
                    'depositId' => $depositId,
                    'amount' => (string) $amount,
                    'currency' => 'XAF',
                    'correspondent' => $operator,
                    'description' => $description,
                    'payer' => [
                        'type' => 'MSISDN',          // Requis pour spécifier le type d'identifiant
                        'address' => [
                            'value' => $formattedPhone // Requis pour encapsuler le numéro
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