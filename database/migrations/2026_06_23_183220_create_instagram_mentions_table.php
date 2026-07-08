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
        Schema::create('instagram_mentions', function (Blueprint $table) {
            $table->id();
            $table->string('sender')->nullable();
            $table->string('notification_text')->nullable();
            $table->text('post_message')->nullable();
            $table->string('post_link')->unique()->nullable();
            $table->string('message_type')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instagram_mentions');
    }
};
