<?php

namespace App\Settings;

class SettingBlog extends Data
{
    public string $api_secret_key_rss_export;
    public string $telegram_bot;

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
        $settings->telegram_bot = $dataSettings['telegram_bot'] ?? "";

        return $settings->save();
    }
}
