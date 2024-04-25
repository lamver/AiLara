<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        try {
            $this->migrator->add('general.site_name', 'AiLara');
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('general.site_active', true);
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
        }
    }
};
