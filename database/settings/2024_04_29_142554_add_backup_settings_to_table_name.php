<?php

use App\Settings\SettingGeneral;
use Spatie\LaravelSettings\Migrations\SettingsMigration;
use Illuminate\Support\Facades\Log;

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
            Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('general.backup_status', true);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('general.backup_musqldump', true);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

};
