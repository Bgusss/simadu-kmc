<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'facebook_comment_mentions',
            function (Blueprint $table) {

                $table->text(
                    'comment_message'
                )->nullable()
                 ->after(
                    'notification_text'
                 );

            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'facebook_comment_mentions',
            function (Blueprint $table) {

                $table->dropColumn(
                    'comment_message'
                );

            }
        );
    }
};