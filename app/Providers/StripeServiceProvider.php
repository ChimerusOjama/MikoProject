<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Stripe\Stripe;

class StripeServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        
        \Log::info("🔑 Stripe ServiceProvider initialisé");
    }
}