<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaceEncoding extends Model
{
    use HasFactory;

    /**
     * Champs pouvant être remplis automatiquement.
     */
    protected $fillable = [
        'user_id',
        'reference_number',
        'encoding',
    ];

    /**
     * Une empreinte faciale appartient à un employé.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}