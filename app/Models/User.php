<?php

namespace App\Models;

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
        'email',
        'password',
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
            'password'          => 'hashed',
        ];
    }

    // ─── Role helpers ────────────────────────────────────────────────────────

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

    // ─── Relations ───────────────────────────────────────────────────────────

    public function konseli()
    {
        return $this->hasOne(Konseli::class, 'id_user', 'id_user');
    }

    public function konselor()
    {
        return $this->hasOne(Konselor::class, 'id_user', 'id_user');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'id_user', 'id_user');
    }
}
