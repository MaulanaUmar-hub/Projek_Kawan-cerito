<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_konselings', function (Blueprint $table) {
            $table->id('id_pengajuan');
            $table->foreignId('id_konseli')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_konselor')->constrained('users')->onDelete('cascade');

            // Manual foreign key karena primary key assessments bukan 'id'
            $table->unsignedBigInteger('id_assessment');
            $table->foreign('id_assessment')
                ->references('id_assessment')
                ->on('assessments')
                ->onDelete('cascade');

            // Manual foreign key karena primary key jadwal bukan 'id'
            $table->unsignedBigInteger('id_jadwal');
            $table->foreign('id_jadwal')
                ->references('id_jadwal')
                ->on('jadwal')
                ->onDelete('cascade');

            $table->string('status_pengajuan');
            $table->string('link_whatsapp')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_konselings');
    }
};
