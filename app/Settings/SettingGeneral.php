<?php

namespace App\Settings;

use App\Services\Modules\Module;
use Illuminate\Support\Facades\Log;

class SettingGeneral extends Data
{

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

    /**  @var string */
    public string $site_language;

    /**  @var string */
    public string $mail_mailer;

    /**  @var string */
    public string $mail_host;

    /**  @var string */
    public string $mail_port;

    /**  @var string */
    public string $mail_username;

    /**  @var string */
    public string $mail_password;

    /**  @var string */
    public string $mail_encryption;

    /**  @var string */
    public string $mail_from_address;

    /**  @var string */
    public string $mail_from_name;


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
        $settings->seo_title = $dataSettings['seo_title'] ?? "";
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
        $settings->backup_musqldump_path = $dataSettings['backup_musqldump_path'] ?? "";
        $settings->backup_musqldump = key_exists('backup_musqldump', $dataSettings) ?  (bool) $dataSettings['backup_musqldump'] : false;
        $settings->backup_status = key_exists('backup_status', $dataSettings) ?  (bool) $dataSettings['backup_status'] : false;

        $settings->site_language = $dataSettings['site_language'] ?? "";
        $backupFrequency = SettingGeneral::BACKUP_FREQUENCY;

        if (key_exists($dataSettings['backup_frequency'], $backupFrequency)){
            $backupFrequency[$dataSettings['backup_frequency']] = true;
            $settings->backup_frequency = $backupFrequency;
        }

        $settings->mail_from_name = $dataSettings['mail_from_name'] ?? "";
        $settings->mail_from_address = $dataSettings['mail_from_address'] ?? "";
        $settings->mail_encryption = $dataSettings['mail_encryption'] ?? "";
        $settings->mail_password = $dataSettings['mail_password'] ?? "";
        $settings->mail_username = $dataSettings['mail_username'] ?? "";
        $settings->mail_port = $dataSettings['mail_port'] ?? "";
        $settings->mail_host = $dataSettings['mail_host'] ?? "";
        $settings->mail_mailer = $dataSettings['mail_mailer'] ?? "";

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
