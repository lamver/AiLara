<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SettingGeneral extends Settings
{
    const TYPE_STRING = 'string';
    const TYPE_TEXT = 'text';
    const TYPE_INT = 'integer';
    const TYPE_BOOLEAN = 'boolean';

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
}
