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
        Schema::table('ticket_responses', function (Blueprint $table) {
            $table->string('attachment')->nullable()->after('message');
        });

        Schema::table('ticket_status_logs', function (Blueprint $table) {
            $table->string('attachment')->nullable()->after('note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_status_logs', function (Blueprint $table) {
            $table->dropColumn('attachment');
        });

        Schema::table('ticket_responses', function (Blueprint $table) {
            $table->dropColumn('attachment');
        });
    }
};
