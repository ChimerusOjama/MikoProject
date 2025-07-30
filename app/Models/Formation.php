<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'titre',
        'description_courte',
        'description_longue',
        'categorie',
        'niveau',
        'prix',
        'duree_mois',
        'places_disponibles',
        'stripe_price_id',
        'stripe_product_id',
        'status',
        'image_url',
        'date_debut',
        'date_fin'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];


    public function inscriptions()
    {
        return $this->hasMany(\App\Models\Inscription::class);
    }
}
