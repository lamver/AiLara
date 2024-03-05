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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->default(0);
            $table->mediumText('user_params')->nullable();
            $table->mediumText('result')->nullable();
            $table->integer('status')->default(\App\Models\Tasks::STATUS_CREATED);
            $table->integer('access_type')->default(\App\Models\Tasks::ACCESS_TYPE_PUBLIC);
            $table->integer('answer_type')->default(\App\Models\Tasks::TYPE_ANSWER_TEXT);
            $table->bigInteger('task_id');
            $table->integer('form_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
