<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom',
        'email',
        'password',
        'prenom',
        'matricule',
        'telephone',
        'service',
        'role',
        'rfid_uid',
        'photo',
        'face_encoding',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function pointages()
    {
        return $this->hasMany(Pointage::class);
    }

    /**
    * Les empreintes faciales de l'employé.
    *
    * Un employé peut avoir plusieurs références faciales.
    */
    public function faceEncodings()
    {
        return $this->hasMany(FaceEncoding::class);
    }


    public function schedules()
    {
        return $this->hasOne(Schedule::class);
    }

    public function fraudLogs()
    {
        return $this->hasMany(FraudLog::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
