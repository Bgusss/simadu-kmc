<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('ai_classifications', function (Blueprint $table) {

            $table->id();

            $table->foreignId('complaint_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string(
                'suggested_category'
            );

            $table->string(
                'suggested_sub_category'
            );

            $table->json(
                'suggested_opds'
            )->nullable();

            $table->enum(
                'priority',
                [
                    'Rendah',
                    'Sedang',
                    'Tinggi'
                ]
            );

            $table->decimal(
                'confidence',
                5,
                2
            );

            $table->text(
                'reasoning'
            )->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'ai_classifications'
        );
    }
};