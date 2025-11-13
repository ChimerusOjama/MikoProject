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
        'montant',
        'choixForm',
        'stripe_session_id',
        'statut_paiement',
        'message',
        'status',
        'payment_date',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value ?? 'Accepté';
    }
}