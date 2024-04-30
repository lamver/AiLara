<?php

namespace App\Helpers;

use App\Settings\SettingGeneral;
use Spatie\LaravelSettings\Settings as SpatieSettings;

class Settings
{
    static public function load($settingsModel = SettingGeneral::class): SpatieSettings
    {
        return new $settingsModel();
    }

}
