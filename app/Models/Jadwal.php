<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwal';
    protected $primaryKey = 'id_jadwal';

    protected $fillable = [
        'id_konselor',
        'tanggal',
        'jam',
        'status_jadwal',
        'tipe_konseling',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function konselor()
    {
        return $this->belongsTo(Konselor::class, 'id_konselor', 'id_konselor');
    }

    public function pengajuan()
    {
        return $this->hasOne(PengajuanKonseling::class, 'id_jadwal', 'id_jadwal');
    }
}
