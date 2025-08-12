<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
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

    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }
}