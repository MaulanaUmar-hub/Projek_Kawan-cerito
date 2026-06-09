<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $primaryKey = 'id_user';
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'asal',
        'email',
        'password',
        'no_hp',
        'gender',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Helper cek role
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isKonseli(): bool
    {
        return $this->role === 'konseli';
    }
    public function isKonselor(): bool
    {
        return $this->role === 'konselor';
    }

    // Relasi
    public function konseli()
    {
        return $this->hasOne(Konseli::class, 'id_user', 'id_user');
    }

    public function konselor()
    {
        return $this->hasOne(Konselor::class, 'id_user', 'id_user');
    }

    public function assessments()
    {
        return $this->hasManyThrough(
            Assessment::class,
            Konseli::class,
            'id_user',
            'id_konseli',
            'id_user',
            'id_konseli'
        );
    }

    public function jadwals()
    {
        return $this->hasManyThrough(
            Jadwal::class,
            Konselor::class,
            'id_user',
            'id_konselor',
            'id_user',
            'id_konselor'
        );
    }

    public function pengajuanAsKonseli()
    {
        return $this->hasManyThrough(
            PengajuanKonseling::class,
            Konseli::class,
            'id_user',
            'id_konseli',
            'id_user',
            'id_konseli'
        );
    }

    public function pengajuanAsKonselor()
    {
        return $this->hasManyThrough(
            PengajuanKonseling::class,
            Konselor::class,
            'id_user',
            'id_konselor',
            'id_user',
            'id_konselor'
        );
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'id_user');
    }
}
