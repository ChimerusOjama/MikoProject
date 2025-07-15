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
        'categorie',
        'niveau',
        'prix',
        'duree_mois',
        'image_url'
    ];
}
