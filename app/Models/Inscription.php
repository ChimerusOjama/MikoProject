<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'formation_id',
        'name',
        'email',
        'phone',
        'address',
        'choixForm',
        'message',
        'status',
        'statut_paiement',
        'stripe_session_id',
        'payment_date',
        'date_annulation'
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'date_annulation' => 'datetime'
    ];

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec la formation
    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    // Relation avec les paiements
    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    // Accessor pour obtenir le montant total de la formation (via la relation)
    public function getMontantTotalAttribute()
    {
        return $this->formation ? $this->formation->prix : 0;
    }

    // Accessor pour obtenir le montant total payé
    public function getMontantPayeAttribute()
    {
        return $this->paiements()
            ->where('statut', '!=', 'annulé')
            ->sum('montant');
    }

    // Accessor pour obtenir le montant restant à payer
    public function getMontantRestantAttribute()
    {
        $montantTotal = $this->montant_total;
        $montantPaye = $this->montant_paye;
        
        return max(0, $montantTotal - $montantPaye);
    }

    // Accessor pour obtenir le prix formaté
    public function getFormattedMontantTotalAttribute()
    {
        return number_format($this->montant_total, 0, ',', ' ') . ' FCFA';
    }

    // Accessor pour obtenir le montant payé formaté
    public function getFormattedMontantPayeAttribute()
    {
        return number_format($this->montant_paye, 0, ',', ' ') . ' FCFA';
    }

    // Accessor pour obtenir le montant restant formaté
    public function getFormattedMontantRestantAttribute()
    {
        return number_format($this->montant_restant, 0, ',', ' ') . ' FCFA';
    }

    // Méthode pour vérifier si l'inscription est payée en totalité
    public function isCompletelyPaid()
    {
        return $this->montant_restant <= 0;
    }

    // Méthode pour vérifier si l'inscription a des paiements partiels
    public function hasPartialPayments()
    {
        return $this->paiements()
            ->where('statut', 'partiel')
            ->exists();
    }

    // Méthode pour obtenir le nombre d'accounts (paiements partiels)
    public function getAccountsCountAttribute()
    {
        return $this->paiements()
            ->where('account_type', 'like', 'account_%')
            ->where('statut', '!=', 'annulé')
            ->count();
    }
}