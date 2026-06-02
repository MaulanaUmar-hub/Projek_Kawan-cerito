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
        Schema::create('pengajuan_konselings', function (Blueprint $table) {
            $table->id('id_pengajuan');
            $table->foreignId('id_konseli')->constrained('konseli', 'id_konseli')->cascadeOnDelete();
            $table->foreignId('id_konselor')->constrained('konselor', 'id_konselor')->cascadeOnDelete();
            $table->foreignId('id_assessment')->constrained('assessments', 'id_assessment')->cascadeOnDelete();
            $table->foreignId('id_jadwal')->constrained('jadwal', 'id_jadwal')->cascadeOnDelete();
            $table->string('status_pengajuan')->default('menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_konselings');
    }
};
