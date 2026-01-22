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
        'status',
        'date_debut',
        'date_fin',
        'duree_mois',
        'places_disponibles',
        'image_url',
        'stripe_price_id',
        'stripe_product_id',
    ];

    protected $casts = [
        'prix' => 'integer',
        'duree_mois' => 'integer',
        'places_disponibles' => 'integer',
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    // Relation avec les inscriptions
    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    // Accessor pour obtenir le prix formaté
    public function getFormattedPrixAttribute()
    {
        return number_format($this->prix, 0, ',', ' ') . ' FCFA';
    }

    // Méthode pour vérifier si la formation a des inscriptions
    public function hasInscriptions()
    {
        return $this->inscriptions()->exists();
    }

    // Méthode pour obtenir le nombre d'inscriptions
    public function getInscriptionsCountAttribute()
    {
        return $this->inscriptions()->count();
    }
}