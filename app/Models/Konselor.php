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
        'no_hp',
        'gender',
        'link_whatsapp',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
