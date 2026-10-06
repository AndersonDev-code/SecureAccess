<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Rapport extends Model
{
    /**
     * Table associée au modèle.
     */
    protected $table = 'rapports';

    /**
     * Champs pouvant être remplis.
     */
    protected $fillable = [
        'type_rapport',
        'user_id',
        'date_debut',
        'date_fin',
        'nom_fichier',
        'nombre_pointages',
    ];

    /**
     * Conversion des types.
     */
    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    /**
     * Un rapport peut appartenir à un employé.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}