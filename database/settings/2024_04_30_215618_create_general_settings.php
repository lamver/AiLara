<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        try {
            $this->migrator->delete('general.test');
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('general.home_module', \App\Services\Modules\Module::MODULE_AI_FORM);
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('general.favicon', 'https://aisearch.ru/cdn-cgi/image/fit=contain,width=16,height=16,compression=fast/files/0/544222/logotip_bloga_pro_neironnye_seti_i_iskusstvennyi_intelekt_544222.png');
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('general.custom_css', '');
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('general.custom_js', '');
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('general.seo_title', 'Another wonderful site on AILara');
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('general.seo_description', 'Another wonderful site on AILara The neural network works wonders');
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
        }
    }
};
