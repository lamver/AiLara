<?php

use App\Settings\SettingGeneral;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            $this->migrator->add('general.backup_frequency', SettingGeneral::BACKUP_FREQUENCY);
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
        }
        try {
            $this->migrator->add('general.backup_status', true);
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
        }
    }

};
