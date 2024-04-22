<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SettingGeneral extends Settings
{

    public string $site_name;

    public bool $site_active;
    public bool $api_key;

    public static function group(): string
    {
        return 'general';
    }

    public function up(): void
    {
        $this->migrator->add('general.site_name', 'Spatie');
        $this->migrator->add('general.site_active', true);
    }
}
