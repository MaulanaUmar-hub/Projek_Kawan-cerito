<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konseli extends Model
{
    protected $table = 'konseli';
    protected $primaryKey = 'id_konseli';

    protected $fillable = [
        'id_user',
        'asal',
        'no_hp',
        'gender',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'id_konseli', 'id_konseli');
    }

    public function pengajuanKonselings()
    {
        return $this->hasMany(PengajuanKonseling::class, 'id_konseli', 'id_konseli');
    }
}
