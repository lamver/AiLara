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
        Schema::create('modules_common_configs', function (Blueprint $table) {
            $table->id();
            $table->string('const_module_name', 100)->unique();
            $table->boolean('use_on_front')->default(true);
            $table->string('prefix_uri', 50)->unique();
            $table->timestamps();
        });

        \Illuminate\Support\Facades\Artisan::call('db:seed --class=ModuleCommonConfigSeeder');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules_common_configs');
    }
};
