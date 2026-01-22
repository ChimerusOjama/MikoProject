<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    // Constantes pour les modes de paiement (basées sur votre migration)
    public const MODES = [
        'mobile money' => 'Mobile Money',
        'carte banquaire' => 'Carte bancaire',
        'airtel money' => 'Airtel Money',
        'especes' => 'Espèces'
    ];

    // Constantes pour les statuts (basées sur votre migration)
    public const STATUTS = [
        'complet' => 'Complet',
        'partiel' => 'Partiel',
        'annulé' => 'Annulé'
        // Note: 'en_attente' n'est pas dans votre migration initiale
    ];

    protected $fillable = [
        'inscription_id',
        'montant',
        'mode', 
        'reference',
        'statut',
        'date_paiement',
        'preuve_path',
        'stripe_payment_id'
    ];

    protected $casts = [
        'date_paiement' => 'datetime',
        'montant' => 'decimal:2'
    ];

    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }

    public function getFormattedDatePaiementAttribute()
    {
        return $this->date_paiement->format('d/m/Y');
    }

    public function getFormattedMontantAttribute()
    {
        return number_format($this->montant, 0, ',', ' ') . ' FCFA';
    }

    // Accessor pour obtenir le label du mode
    public function getModeLabelAttribute()
    {
        return self::MODES[$this->mode] ?? $this->mode;
    }

    // Accessor pour obtenir le label du statut
    public function getStatutLabelAttribute()
    {
        return self::STATUTS[$this->statut] ?? $this->statut;
    }
}