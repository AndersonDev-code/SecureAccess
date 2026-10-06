<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FraudLog extends Model
{
    protected $fillable = [
        'user_id',
        'rfid_uid',
        'photo_capture',
        'ip_station',
        'type',
        'description',
        'heure_fraude',
    ];

    protected $casts = [
        'heure_fraude' => 'datetime',
    ];  

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
