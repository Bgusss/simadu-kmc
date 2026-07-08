<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk menambahkan kolom sistem tiket
     * dan membuat tabel pendukung (responses, status logs).
     */
    public function up(): void
    {
        // ──────────────────────────────────────────────
        // Modifikasi tabel users — tambah kolom login OPD
        // ──────────────────────────────────────────────
        Schema::table('users', function (Blueprint $table) {

            $table->string('username')->unique()->nullable()->after('name');

            $table->enum('role', ['admin', 'opd'])->default('opd')->after('email');

            $table->foreignId('opd_id')
                ->nullable()
                ->after('role')
                ->constrained('opds')
                ->nullOnDelete();
        });

        // ──────────────────────────────────────────────
        // Modifikasi tabel tickets — tambah kolom workflow
        // ──────────────────────────────────────────────
        Schema::table('tickets', function (Blueprint $table) {

            $table->enum('status', [
                'diterima',
                'diteruskan',
                'dibaca',
                'diproses',
                'dijawab',
                'selesai',
                'eskalasi',
            ])->default('diterima')->after('complaint');

            $table->foreignId('assigned_opd_id')
                ->nullable()
                ->after('status')
                ->constrained('opds')
                ->nullOnDelete();

            $table->enum('priority', ['rendah', 'sedang', 'tinggi'])
                ->default('sedang')
                ->after('assigned_opd_id');

            // Format: KMC-YYYYMMDD-XXXX
            $table->string('tracking_number')->unique()->nullable()->after('priority');

            $table->dateTime('sla_deadline')->nullable()->after('tracking_number');
            $table->dateTime('escalated_at')->nullable()->after('sla_deadline');
            $table->integer('escalation_count')->default(0)->after('escalated_at');
            $table->dateTime('read_at')->nullable()->after('escalation_count');
            $table->dateTime('responded_at')->nullable()->after('read_at');

            $table->decimal('ai_confidence', 5, 2)->nullable()->after('responded_at');
            $table->text('ai_reasoning')->nullable()->after('ai_confidence');
        });

        // ──────────────────────────────────────────────
        // Buat tabel ticket_responses — balasan tiket
        // ──────────────────────────────────────────────
        Schema::create('ticket_responses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ticket_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('message');

            $table->timestamps();
        });

        // ──────────────────────────────────────────────
        // Buat tabel ticket_status_logs — riwayat status
        // ──────────────────────────────────────────────
        Schema::create('ticket_status_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ticket_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('from_status')->nullable();
            $table->string('to_status');

            $table->foreignId('changed_by')
                ->nullable()
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->text('note')->nullable();

            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Batalkan semua perubahan migrasi.
     */
    public function down(): void
    {
        // Drop tabel baru terlebih dahulu
        Schema::dropIfExists('ticket_status_logs');
        Schema::dropIfExists('ticket_responses');

        // Hapus kolom tambahan di tabel tickets
        Schema::table('tickets', function (Blueprint $table) {

            $table->dropForeign(['assigned_opd_id']);

            $table->dropColumn([
                'status',
                'assigned_opd_id',
                'priority',
                'tracking_number',
                'sla_deadline',
                'escalated_at',
                'escalation_count',
                'read_at',
                'responded_at',
                'ai_confidence',
                'ai_reasoning',
            ]);
        });

        // Hapus kolom tambahan di tabel users
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['opd_id']);

            $table->dropColumn([
                'username',
                'role',
                'opd_id',
            ]);
        });
    }
};
