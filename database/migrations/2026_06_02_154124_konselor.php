<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('konselor', function (Blueprint $table) {
            $table->id('id_konselor');
            $table->foreignId('id_user')->constrained('users', 'id_user')->cascadeOnDelete();
            $table->string('spesialisasi')->nullable();
            $table->string('peminatan')->nullable();
            $table->string('catatan_profil')->nullable();
            $table->string('no_hp')->nullable();
            $table->enum('gender', ['L', 'P', 'N'])->nullable();
            $table->string('link_whatsapp')->nullable();
            $table->enum('status', ['pending', 'aktif', 'ditolak'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konselor');
    }
};
