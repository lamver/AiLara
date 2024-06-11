<?php

use App\Settings\SettingBlog;
use Spatie\LaravelSettings\Migrations\SettingsMigration;
use Illuminate\Support\Facades\Log;

return new class extends SettingsMigration
{
    public function up(): void
    {
        try {
            $this->migrator->add('blog.pagination_type',  SettingBlog::PAGINATION_TYPE[0]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
};
