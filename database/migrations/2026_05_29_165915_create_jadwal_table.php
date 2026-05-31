<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id('id_jadwal');
            $table->foreignId('id_konselor')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam');
            $table->string('status_jadwal');
            $table->string('tipe_konseling');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
