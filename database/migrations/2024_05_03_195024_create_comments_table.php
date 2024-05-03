<?php

declare(strict_types=1);

/**
 * Laravel Commentable Package.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * The name of default lft column.
     */
    const LFT = '_lft';

    /**
     * The name of default rgt column.
     */
    const RGT = '_rgt';

    /**
     * The name of default parent id column.
     */
    const PARENT_ID = 'parent_id';

    /**
     * Insert direction.
     */
    const BEFORE = 1;

    /**
     * Insert direction.
     */
    const AFTER = 2;

    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->boolean('active')->default(false);
            $table->text('body');
            $table->morphs('commentable');
            $table->morphs('creator');
            self::columns($table);
            $table->timestamps();
        });
    }

    /**
     * Add default nested set columns to the table. Also create an index.
     *
     * @param Blueprint $table
     */
    public static function columns(Blueprint $table): void
    {
        $table->unsignedInteger(self::LFT)->default(0);
        $table->unsignedInteger(self::RGT)->default(0);
        $table->unsignedInteger(self::PARENT_ID)->nullable();

        $table->index(static::getDefaultColumns());
    }

    /**
     * Get a list of default columns.
     *
     * @return array
     */
    public static function getDefaultColumns(): array
    {
        return [ static::LFT, static::RGT, static::PARENT_ID ];
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }

}
