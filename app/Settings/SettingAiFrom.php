<?php

namespace App\Settings;

use App\Services\Modules\Module;

class SettingAiFrom extends Data
{
    public bool $home_page_view_forms_page;
    public string $home_page_view_forms_ids;
    public string $home_page_view_forms_template;
    public bool $home_page_view_posts;
    public string $home_page_category_ids;
    public string $home_page_seo_title;
    public string $home_page_seo_description;
    public string $home_page_page_image;

    public static function group(): string
    {
        return 'aiForm';
    }

    /**
     * @param array $dataSettings
     * @param SettingAiFrom $settings
     * @return SettingAiFrom
     */
    public function prepareAndSave(array $dataSettings, SettingAiFrom $settings): SettingAiFrom
    {
        $settings->home_page_view_forms_page = $dataSettings['home_page_view_forms_page'] ?? "";

        if (array_key_exists('form_ids', $dataSettings)) {
            $settings->home_page_view_forms_ids = json_encode($dataSettings['form_ids']);
        } else {
            $settings->home_page_view_forms_ids = json_encode([]);
        }

        //$settings->home_page_view_forms_ids = (bool) array_key_exists('home_page_view_forms_ids', $dataSettings);
        $settings->home_page_view_forms_template = $dataSettings['home_page_view_forms_template'] ?? "";
        $settings->home_page_view_posts = (bool) array_key_exists('home_page_view_posts', $dataSettings);

        if (array_key_exists('category_ids', $dataSettings)) {
            $settings->home_page_category_ids = json_encode($dataSettings['category_ids']);
        } else {
            $settings->home_page_category_ids = json_encode([]);
        }

        $settings->home_page_seo_title = $dataSettings['home_page_seo_title'] ?? '';
        $settings->home_page_seo_description = $dataSettings['home_page_seo_description'] ?? '';
        $settings->home_page_page_image = $dataSettings['home_page_page_image'] ?? "";

        return $settings->save();
    }
}
