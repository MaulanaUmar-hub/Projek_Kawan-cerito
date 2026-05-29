<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_konseling', function (Blueprint $table) {
            $table->id('id_hasil');

            // Manual foreign key karena primary key pengajuan_konselings bukan 'id'
            $table->unsignedBigInteger('id_pengajuan');
            $table->foreign('id_pengajuan')
                ->references('id_pengajuan')
                ->on('pengajuan_konselings')
                ->onDelete('cascade');

            $table->text('catatan_konseling');
            $table->text('rekomendasi');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_konseling');
    }
};
