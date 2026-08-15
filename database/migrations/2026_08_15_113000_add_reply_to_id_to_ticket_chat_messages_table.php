<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_chat_messages', function (Blueprint $table) {
            $table->foreignId('reply_to_id')->nullable()->constrained('ticket_chat_messages')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ticket_chat_messages', function (Blueprint $table) {
            $table->dropForeign(['reply_to_id']);
            $table->dropColumn('reply_to_id');
        });
    }
};
