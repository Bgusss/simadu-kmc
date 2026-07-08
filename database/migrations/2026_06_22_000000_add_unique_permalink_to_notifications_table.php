<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        /*
         * Hapus duplikat yang sudah ada terlebih dahulu
         * sebelum menambahkan unique constraint.
         * Simpan hanya record pertama (id terkecil) per permalink.
         */
        $duplicates = DB::table('notifications')
            ->select('permalink')
            ->whereNotNull('permalink')
            ->groupBy('permalink')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('permalink');

        foreach ($duplicates as $permalink) {
            $ids = DB::table('notifications')
                ->where('permalink', $permalink)
                ->orderBy('id')
                ->pluck('id');

            // Hapus semua kecuali yang pertama
            $ids->shift();
            DB::table('notifications')
                ->whereIn('id', $ids)
                ->delete();
        }

        /*
         * Ubah tipe kolom permalink menjadi string(500)
         * agar bisa diindeks (TEXT tidak bisa unique di MySQL).
         */
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('permalink', 500)
                ->nullable()
                ->unique()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropUnique(['permalink']);
            $table->text('permalink')
                ->nullable()
                ->change();
        });
    }
};
