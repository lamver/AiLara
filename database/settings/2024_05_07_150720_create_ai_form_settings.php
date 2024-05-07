<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;
use Illuminate\Support\Facades\Log;

return new class extends SettingsMigration
{
    public function up(): void
    {
        try {
            $this->migrator->add('aiForm.home_page_view_forms_page', true);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('aiForm.home_page_view_forms_ids', '');
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('aiForm.home_page_view_forms_template', 'default');
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('aiForm.home_page_view_posts', true);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('aiForm.home_page_category_ids', '');
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('aiForm.home_page_seo_title', '');
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('aiForm.home_page_seo_description', '');
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        try {
            $this->migrator->add('aiForm.home_page_page_image', '');
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
};
