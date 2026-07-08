<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('notification_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('ticket_number')->unique();
            $table->dateTime('ticket_time');
            $table->string('platform')->nullable();
            $table->string('reporter_name')->nullable();
            $table->string('reporter_link')->nullable();
            $table->string('category')->nullable();
            $table->string('sub_category')->nullable();
            $table->string('opd_related')->nullable();
            $table->text('complaint')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};