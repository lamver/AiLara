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
        Schema::table('telegraph_chats', function (Blueprint $table) {
            $table->integer('user_step')->nullable()->default(null);
            $table->json('user_input')->nullable();
            // Add more fields as needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('telegraph_chats', function (Blueprint $table) {
            $table->dropColumn('user_step');
            $table->dropColumn('user_input');
            // Drop more fields as needed
        });
    }
};
