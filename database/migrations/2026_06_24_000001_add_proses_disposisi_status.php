<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tambahkan value 'proses_disposisi' ke enum status pada tabel tickets.
     * Status ini digunakan untuk menandakan tiket yang sudah melewati SLA 1x24 jam
     * tanpa respon dari OPD.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE tickets MODIFY COLUMN status ENUM('diterima','diteruskan','dibaca','diproses','dijawab','selesai','eskalasi','proses_disposisi') DEFAULT 'diterima'");
        }
    }

    /**
     * Kembalikan enum status ke semula (tanpa proses_disposisi).
     */
    public function down(): void
    {
        // Update tiket yang berstatus proses_disposisi ke diteruskan sebelum mengubah enum
        DB::table('tickets')->where('status', 'proses_disposisi')->update(['status' => 'diteruskan']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE tickets MODIFY COLUMN status ENUM('diterima','diteruskan','dibaca','diproses','dijawab','selesai','eskalasi') DEFAULT 'diterima'");
        }
    }
};
