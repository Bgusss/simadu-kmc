<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {

            $table->foreignId('category_id')
                ->nullable()
                ->after('message')
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('sub_category_id')
                ->nullable()
                ->after('category_id')
                ->constrained('sub_categories')
                ->nullOnDelete();

            $table->foreignId('opd_id')
                ->nullable()
                ->after('sub_category_id')
                ->constrained()
                ->nullOnDelete();

            $table->string('priority')
                ->nullable()
                ->after('opd_id');

            $table->integer('confidence')
                ->nullable()
                ->after('priority');

            $table->timestamp('ai_processed_at')
                ->nullable()
                ->after('confidence');

        });
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {

            $table->dropConstrainedForeignId(
                'category_id'
            );

            $table->dropConstrainedForeignId(
                'sub_category_id'
            );

            $table->dropConstrainedForeignId(
                'opd_id'
            );

            $table->dropColumn([
                'priority',
                'confidence',
                'ai_processed_at'
            ]);

        });
    }
};