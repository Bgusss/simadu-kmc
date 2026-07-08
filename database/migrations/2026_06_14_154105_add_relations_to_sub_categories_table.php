<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table(
            'sub_categories',
            function (Blueprint $table) {

                $table->foreignId(
                    'category_id'
                )
                ->nullable()
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();

                $table->foreignId(
                    'opd_id'
                )
                ->nullable()
                ->after('category_id')
                ->constrained()
                ->cascadeOnDelete();

            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'sub_categories',
            function (Blueprint $table) {

                $table->dropForeign([
                    'category_id'
                ]);

                $table->dropForeign([
                    'opd_id'
                ]);

                $table->dropColumn([

                    'category_id',

                    'opd_id'

                ]);

            }
        );
    }
};