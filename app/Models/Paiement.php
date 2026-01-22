<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Paiement extends Model
{
    use HasFactory;

    // Constantes pour les modes de paiement
    public const MODES = [
        'mobile money' => 'Mobile Money',
        'carte banquaire' => 'Carte bancaire',
        'airtel money' => 'Airtel Money',
        'especes' => 'Espèces'
    ];

    // Constantes pour les statuts
    public const STATUTS = [
        'complet' => 'Complet',
        'partiel' => 'Partiel',
        'annulé' => 'Annulé'
    ];

    // Constantes pour les types de compte
    public const ACCOUNT_TYPES = [
        'principal' => 'Principal',
        'account_1' => 'Account 1',
        'account_2' => 'Account 2'
    ];

    // Constantes pour les types de paiement
    public const PAYMENT_TYPES = [
        'stripe' => 'Stripe (en ligne)',
        'manuel' => 'Manuel'
    ];

    protected $fillable = [
        'inscription_id',
        'montant',
        'mode', 
        'reference',
        'statut',
        'account_type',
        'type_paiement',
        'date_paiement',
        'preuve_path',
        'stripe_payment_id'
    ];

    protected $casts = [
        'date_paiement' => 'datetime',
        'montant' => 'integer'
    ];

    // Relation avec l'inscription
    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }

    // Événements du modèle pour le logging
    protected static function booted()
    {
        static::creating(function ($paiement) {
            Log::channel('paiements')->info('Création paiement en cours', [
                'inscription_id' => $paiement->inscription_id,
                'montant' => $paiement->montant,
                'reference' => $paiement->reference,
                'user_id' => auth()->id() ?? 'system'
            ]);
        });

        static::created(function ($paiement) {
            Log::channel('paiements')->info('Paiement créé avec succès', [
                'paiement_id' => $paiement->id,
                'inscription_id' => $paiement->inscription_id,
                'montant' => $paiement->montant,
                'statut' => $paiement->statut,
                'account_type' => $paiement->account_type,
                'user_id' => auth()->id() ?? 'system'
            ]);
        });

        static::updating(function ($paiement) {
            Log::channel('paiements')->info('Mise à jour paiement en cours', [
                'paiement_id' => $paiement->id,
                'old_data' => $paiement->getOriginal(),
                'new_data' => $paiement->getAttributes(),
                'user_id' => auth()->id() ?? 'system'
            ]);
        });

        static::updated(function ($paiement) {
            Log::channel('paiements')->info('Paiement mis à jour avec succès', [
                'paiement_id' => $paiement->id,
                'inscription_id' => $paiement->inscription_id,
                'user_id' => auth()->id() ?? 'system'
            ]);
        });

        static::deleting(function ($paiement) {
            Log::channel('paiements')->warning('Suppression paiement en cours', [
                'paiement_id' => $paiement->id,
                'inscription_id' => $paiement->inscription_id,
                'montant' => $paiement->montant,
                'user_id' => auth()->id() ?? 'system'
            ]);
        });
    }

    // Accessor pour obtenir la date formatée
    public function getFormattedDatePaiementAttribute()
    {
        return $this->date_paiement->format('d/m/Y H:i');
    }

    // Accessor pour obtenir le montant formaté
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

    // Accessor pour obtenir le label du type de compte
    public function getAccountTypeLabelAttribute()
    {
        return self::ACCOUNT_TYPES[$this->account_type] ?? $this->account_type;
    }

    // Accessor pour obtenir le label du type de paiement
    public function getPaymentTypeLabelAttribute()
    {
        return self::PAYMENT_TYPES[$this->type_paiement] ?? $this->type_paiement;
    }

    // Méthode pour calculer le montant minimum d'un account
    public static function getMinimumAccountAmount()
    {
        return 5000;
    }

    // Méthode pour vérifier si un paiement est un account
    public function isAccount()
    {
        return in_array($this->account_type, ['account_1', 'account_2']);
    }

    // Méthode pour logger une action spécifique
    public static function logAction($action, $data)
    {
        Log::channel('paiements')->info($action, array_merge($data, [
            'user_id' => auth()->id() ?? 'system',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]));
    }
}