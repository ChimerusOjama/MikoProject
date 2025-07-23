<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stripe\Stripe;
use Stripe\HttpClient\CurlClient;

class CheckSslConfig extends Command
{
    protected $signature = 'check:ssl';
    protected $description = 'Vérifie la configuration SSL pour Stripe';

public function handle()
{
    $this->info("=== Vérification de la configuration SSL ===");

    // 1. Vérification du bundle de certificats
    $caBundle = env('CURL_CA_BUNDLE');
    $this->line("Chemin du bundle: " . ($caBundle ?: 'non défini'));

    if ($caBundle) {
        $caBundle = trim($caBundle, '"'); // Nettoyer les guillemets
        $exists = file_exists($caBundle);
        $this->line("Bundle existe: " . ($exists ? '✅ Oui' : '❌ Non'));
        
        if ($exists) {
            $this->line("Taille du bundle: " . filesize($caBundle) . " bytes");
            $this->line("Dernière modification: " . date('Y-m-d H:i:s', filemtime($caBundle)));
        }
    }

    // 2. Test de connexion à Stripe
    $this->info("\nTest de connexion à Stripe...");
    
    try {
        $client = new CurlClient();
        
        // Appel corrigé avec tous les paramètres requis
        $response = $client->request(
            'get', 
            'https://api.stripe.com/v1/checkout/sessions', 
            [],  // headers
            [],  // params
            false, // hasFile
            'v1' // apiMode
        );
        
        $this->info("✅ Connexion réussie! Code HTTP: " . $response->getStatusCode());
    } catch (\Exception $e) {
        $this->error("❌ Échec de la connexion: " . $e->getMessage());
        $this->line("Détails de l'erreur:");
        $this->line($e->getTraceAsString());
    }

    // 3. Configuration système
    $this->info("\nConfiguration système:");
    $this->line("PHP version: " . PHP_VERSION);
    $this->line("cURL version: " . curl_version()['version']);
    $this->line("OpenSSL version: " . OPENSSL_VERSION_TEXT);
    $this->line("curl.cainfo: " . ini_get('curl.cainfo'));
    $this->line("openssl.cafile: " . ini_get('openssl.cafile'));
    $this->info("\n=== Vérification terminée ===");
}

}