<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_konselings', function (Blueprint $table) {
            $table->dropForeign(['id_jadwal']);
            $table->unsignedBigInteger('id_jadwal')->nullable()->change();
            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwal')->nullOnDelete();

            // Usulan jadwal dari konseli
            $table->date('tanggal_usulan')->nullable()->after('id_jadwal');
            $table->time('jam_usulan')->nullable()->after('tanggal_usulan');
            $table->string('tipe_konseling_usulan')->nullable()->after('jam_usulan');

            // Reschedule dari konselor
            $table->date('tanggal_reschedule')->nullable()->after('tipe_konseling_usulan');
            $table->time('jam_reschedule')->nullable()->after('tanggal_reschedule');
            $table->text('catatan_reschedule')->nullable()->after('jam_reschedule');

            // Alasan jika ditolak
            $table->text('alasan_penolakan')->nullable()->after('catatan_reschedule');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_konselings', function (Blueprint $table) {
            $table->dropForeign(['id_jadwal']);
            $table->unsignedBigInteger('id_jadwal')->nullable(false)->change();
            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwal')->cascadeOnDelete();

            $table->dropColumn([
                'tanggal_usulan',
                'jam_usulan',
                'tipe_konseling_usulan',
                'tanggal_reschedule',
                'jam_reschedule',
                'catatan_reschedule',
                'alasan_penolakan',
            ]);
        });
    }
};
