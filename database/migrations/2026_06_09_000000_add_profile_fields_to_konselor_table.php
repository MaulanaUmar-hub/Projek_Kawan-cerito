<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('konselor', function (Blueprint $table) {
            if (! Schema::hasColumn('konselor', 'peminatan')) {
                $table->string('peminatan')->nullable()->after('spesialisasi');
            }

            if (! Schema::hasColumn('konselor', 'catatan_profil')) {
                $table->text('catatan_profil')->nullable()->after('peminatan');
            }

            if (! Schema::hasColumn('konselor', 'foto')) {
                $table->string('foto')->nullable()->after('gender');
            }
        });
    }

    public function down(): void
    {
        Schema::table('konselor', function (Blueprint $table) {
            foreach (['peminatan', 'catatan_profil', 'foto'] as $column) {
                if (Schema::hasColumn('konselor', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
