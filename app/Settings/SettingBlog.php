<?php

namespace App\Settings;

class SettingBlog extends Data
{
    public string $api_secret_key_rss_export;
    public static function group(): string
    {
        return 'blog';
    }

    /**
     * @param array $dataSettings
     * @param SettingBlog $settings
     * @return SettingBlog
     */
    public function prepareAndSave(array $dataSettings, SettingBlog $settings): SettingBlog
    {

        $settings->api_secret_key_rss_export = $dataSettings['api_secret_key_rss_export'] ?? '';

        return $settings->save();
    }
}
