<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konselor extends Model
{
    protected $table = 'konselor';
    protected $primaryKey = 'id_konselor';

    protected $fillable = [
        'id_user',
        'spesialisasi',
        'peminatan',
        'catatan_profil',
        'no_hp',
        'gender',
        'foto',
        'link_whatsapp',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'id_konselor', 'id_konselor');
    }

    public function pengajuanKonselings()
    {
        return $this->hasMany(PengajuanKonseling::class, 'id_konselor', 'id_konselor');
    }
}
