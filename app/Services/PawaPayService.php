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
        try {
            $response = Http::withToken($this->token)
                ->post($this->baseUrl . '/deposits', [
                    'depositId' => $depositId,
                    'amount' => (string) $amount,
                    'currency' => 'XAF',
                    'phone' => $phone,
                    'correspondent' => $this->detectOperator($phone),
                    'description' => $description
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
     * Détermine automatiquement l'opérateur (Exemple pour le Congo)
     */
    private function detectOperator(string $phone): string
    {
        // Nettoyage rapide du numéro si nécessaire
        if (str_starts_with($phone, '06') || str_starts_with($phone, '+24206') || str_starts_with($phone, '24206')) {
            return 'MTN_CG'; 
        }
        return 'AIRTEL_CG';
    }
}