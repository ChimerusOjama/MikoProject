<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

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
}