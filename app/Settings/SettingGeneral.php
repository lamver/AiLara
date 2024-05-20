<?php

namespace App\Settings;

use App\Services\Modules\Module;
use Illuminate\Support\Facades\Log;

class SettingGeneral extends Data
{

    /** @var string */
    public string $site_name;

    /** @var bool */
    public bool $site_active;

    /** @var string */
    public string $app_name;

    /** @var string */
    public string $logo_path;

    /** @var string */
    public string $logo_title;

    /** @var int */
    public int $logo_height_px;

    /** @var int */
    public int $logo_width_px;

    /** @var string */
    public string $counter_external_code;

    /** @var string */
    public string $api_key_aisearch;

    /** @var string */
    public string $api_host;

    /**  @var string */
    public string $admin_prefix;
    public string $home_module;
    public string $favicon;
    public string $custom_css;
    public string $seo_title;
    public string $seo_description;

    /**
     * Get the group name.
     *
     * @return string
     */
    public static function group(): string
    {
        return 'general';
    }

    /**
     * @param array $dataSettings
     * @param \App\Settings\SettingGeneral $settings
     *
     * @return \App\Settings\SettingGeneral
     */
    public function prepareAndSave(array $dataSettings, SettingGeneral $settings): SettingGeneral
    {
        $settings->site_name = $dataSettings['site_name'] ?? "";
        $settings->seo_title = $dataSettings['seo_title'] ?? "";
        $settings->site_active = (bool)$dataSettings['site_active'];
        $settings->app_name = $dataSettings['app_name'] ?? "";
        $settings->logo_path = $dataSettings['logo_path'] ?? "";
        $settings->logo_title = $dataSettings['logo_title'] ?? "";
        $settings->logo_height_px = $dataSettings['logo_height_px'];
        $settings->logo_width_px = $dataSettings['logo_width_px'];
        $settings->counter_external_code = $dataSettings['counter_external_code'] ?? "";
        $settings->api_key_aisearch = $dataSettings['api_key_aisearch'] ?? "";
        $settings->api_host = $dataSettings['api_host'] ?? "";
        $settings->admin_prefix = $dataSettings['admin_prefix'] ?? "";
        $settings->home_module = (isset(Module::MODULE_CONFIG[$dataSettings['home_module']])) ? $dataSettings['home_module'] : Module::MODULE_AI_FORM;
        $settings->favicon = $dataSettings['favicon'] ?? "";
        $settings->custom_css = $dataSettings['custom_css'] ?? "";
        $settings->seo_description = $dataSettings['seo_description'] ?? "";

        return $settings->save();
    }

    /**
     * @param string|null $settingName
     *
     * @return mixed|null
     */
    static public function value(string $settingName = null)
    {
        if (is_null($settingName)) {
            return null;
        }

        try {
            return app(SettingGeneral::class)->{$settingName};
        } catch (\Exception $e) {
            Log::error('Settings: ' . $e->getMessage());
        }
        return null;
    }
}
