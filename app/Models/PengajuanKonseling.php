<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanKonseling extends Model
{
    protected $table = 'pengajuan_konselings';
    protected $primaryKey = 'id_pengajuan';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'id_konseli',
        'id_konselor',
        'id_assessment',
        'id_jadwal',
        'status_pengajuan',
        'tanggal_usulan',
        'jam_usulan',
        'tipe_konseling_usulan',
        'tanggal_reschedule',
        'jam_reschedule',
        'catatan_reschedule',
        'alasan_penolakan',
    ];

    protected $casts = [
        'created_at'        => 'datetime',
        'tanggal_usulan'    => 'date',
        'tanggal_reschedule' => 'date',
    ];

    /*
     * Status yang mungkin:
     *   menunggu   — baru diajukan konseli, belum direspons konselor
     *   disetujui  — konselor setuju, id_jadwal diisi
     *   reschedule — konselor ajukan ulang waktu, menunggu konfirmasi konseli
     *   ditolak    — konselor menolak pengajuan
     *   selesai    — sesi sudah dilaksanakan
     */

    public function konseli()
    {
        return $this->belongsTo(Konseli::class, 'id_konseli', 'id_konseli');
    }

    public function konselor()
    {
        return $this->belongsTo(Konselor::class, 'id_konselor', 'id_konselor');
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'id_assessment', 'id_assessment');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
    }

    public function hasil()
    {
        return $this->hasOne(HasilKonseling::class, 'id_pengajuan', 'id_pengajuan');
    }
}
