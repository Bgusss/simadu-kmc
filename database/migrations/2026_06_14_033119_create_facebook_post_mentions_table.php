<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facebook_post_mentions', function (Blueprint $table) {

            $table->id();

            $table->string('post_link')->unique();

            $table->text('notification_text');

            $table->text('post_message')->nullable();

            $table->string('sender')->nullable();

            $table->boolean('is_read')->default(false);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facebook_post_mentions');
    }
};