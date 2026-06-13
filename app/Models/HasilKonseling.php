<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilKonseling extends Model
{
    protected $table = 'hasil_konseling';
    protected $primaryKey = 'id_hasil';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'id_pengajuan',
        'catatan_konseling',
        'rekomendasi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanKonseling::class, 'id_pengajuan', 'id_pengajuan');
    }
}
