<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('duplicate_of_id')->nullable()->after('is_read');
            $table->float('duplicate_similarity')->nullable()->after('duplicate_of_id');
            $table->string('duplicate_status')->nullable()->after('duplicate_similarity');

            $table->foreign('duplicate_of_id')
                ->references('id')
                ->on('notifications')
                ->onDelete('set null');

            $table->index('duplicate_status');
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['duplicate_of_id']);
            $table->dropIndex(['duplicate_status']);
            $table->dropColumn(['duplicate_of_id', 'duplicate_similarity', 'duplicate_status']);
        });
    }
};
