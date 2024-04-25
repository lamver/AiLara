<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        try {
            $this->migrator->add('general.app_name', 'Ai Lara CMF/CMS');
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('general.logo_path', 'https://aisearch.ru/cdn-cgi/image/fit=contain,width=140,height=42,compression=fast/files/0/544222/logotip_bloga_pro_neironnye_seti_i_iskusstvennyi_intelekt_544222.png');
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('general.logo_title', 'Ai lara');
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('general.logo_height_px', 32);
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('general.logo_width_px', 32);
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('general.counter_external_code', '');
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('general.test', 43);
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('general.api_key_aisearch', '');
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('general.api_host', 'api.aisearch.ru');
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('general.admin_prefix', 'admin');
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
        }
    }
};
