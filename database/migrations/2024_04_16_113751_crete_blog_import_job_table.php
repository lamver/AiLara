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
        Schema::create('blog_import_job', function (Blueprint $table) {
            $table->id();
            $table->integer('source_type')->default(\App\Models\Modules\Blog\Import::SOURCE_TYPE_RSS)->comment('rss url or any task');
            $table->integer('status')->default(0);
            $table->boolean('repeating_task')->default(false);
            $table->integer('cron')->default(false);
            $table->integer('category_id')->default(null);
            $table->text('task_source')->comment('url rss or any task');
            $table->longText('result_id_posts')->comment('ids result posts')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('blog_import_job');
    }
};
