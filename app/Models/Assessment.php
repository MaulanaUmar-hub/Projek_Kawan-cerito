<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $table = 'assessments';
    protected $primaryKey = 'id_assessment';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'id_konseli',
        'keluhan',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function konseli()
    {
        return $this->belongsTo(Konseli::class, 'id_konseli', 'id_konseli');
    }

    public function pengajuan()
    {
        return $this->hasOne(PengajuanKonseling::class, 'id_assessment', 'id_assessment');
    }
}
