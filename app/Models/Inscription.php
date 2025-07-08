<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'montant',
        'choixForm',
        'message',
        'status',
    ];

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
