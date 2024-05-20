<?php

use Illuminate\Support\Str;
use Spatie\LaravelSettings\Migrations\SettingsMigration;
use Illuminate\Support\Facades\Log;

return new class extends SettingsMigration
{
    public function up(): void
    {
        try {
            $this->migrator->add('blog.telegram_bot', '');
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
};
