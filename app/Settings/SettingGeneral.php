<?php

namespace App\Settings;

use Illuminate\Http\Request;
use Spatie\LaravelSettings\Settings;

class SettingGeneral extends Settings
{
    const TYPE_STRING = 'string';
    const TYPE_TEXT = 'text';
    const TYPE_INT = 'integer';
    const TYPE_BOOLEAN = 'boolean';

    const TYPE_ARRAY = 'array';

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
    /** @var int */
    public int $test;

    /** @var string */
    public string $api_key_aisearch;

    /** @var string */
    public string $api_host;

    /**  @var string */
    public string $admin_prefix;

    public array $backup_frequency;

    /**  @var array */
    const BACKUP_FREQUENCY = [
        'hourly' => false,
        'daily' => false,
        'weekly' => false,
        'monthly' => false,
        'yearly' => false,
    ];

    /** @var bool */
    public bool $backup_status;

    /** @var bool */
    public bool $backup_musqldump;

    /**  @var string */
    public string $backup_musqldump_path;


    // public bool $api_key;

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
        $settings->site_active = key_exists('site_active', $dataSettings) ?  (bool) $dataSettings['site_active'] : false;
        $settings->app_name = $dataSettings['app_name'] ?? "";
        $settings->logo_path = $dataSettings['logo_path'] ?? "";
        $settings->logo_title = $dataSettings['logo_title'] ?? "";
        $settings->logo_height_px = $dataSettings['logo_height_px'];
        $settings->logo_width_px = $dataSettings['logo_width_px'];
        $settings->logo_width_px = $dataSettings['logo_width_px'];
        $settings->counter_external_code = $dataSettings['counter_external_code'] ?? "";
        $settings->test = $dataSettings['test'];
        $settings->api_key_aisearch = $dataSettings['api_key_aisearch'] ?? "";
        $settings->api_host = $dataSettings['api_host'] ?? "";
        $settings->admin_prefix = $dataSettings['admin_prefix'] ?? "";
        $settings->backup_musqldump_path = $dataSettings['backup_musqldump_path'] ?? "";
        $settings->backup_musqldump = key_exists('backup_musqldump', $dataSettings) ?  (bool) $dataSettings['backup_musqldump'] : false;
        $settings->backup_status = key_exists('backup_status', $dataSettings) ?  (bool) $dataSettings['backup_status'] : false;

        $backupFrequency = SettingGeneral::BACKUP_FREQUENCY;

        if (key_exists($dataSettings['backup_frequency'], $backupFrequency)){
            $backupFrequency[$dataSettings['backup_frequency']] = true;
            $settings->backup_frequency = $backupFrequency;
        }

        return $settings->save();
    }
}
