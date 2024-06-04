<?php

namespace App\Services\Translation;

use App\Settings\SettingGeneral;
use Illuminate\Support\Facades\App;
use Barryvdh\TranslationManager\Manager;

/**
 * Class Translation
 *
 * A class to handle language translations.
 */
class Translation
{

    /**
     * Get the languages supported by the Laravel application.
     *
     * @return array The list of supported languages.
     */
    public static function getLanguages(): array
    {
        $transistor = App::make(Manager::class);
        try {
            return $transistor->getLocales();
        } catch (\Exception $e) {
            return [];
        }

    }

    /**
     * Retrieve an array of languages for the route
     *
     * @return array
     */
    public static function getLanguagesForRoute(): array
    {
        return array_map(function($value) {
            // Make default lang is empty
            return $value === config('app.locale') ? "" : $value;
        }, static::getLanguages());
    }

    /**
     * Check if the current route has a language prefix.
     *
     * @return string|null The language prefix if present, null otherwise.
     */
    public static function checkRoutePrefix(): ?string
    {
        $firstSegment = request()->segment(1);
        if (in_array($firstSegment, self::getLanguages()) && $firstSegment !== config('app.locale') ) {
            return request()->segment(1);
        }

        return null;

    }

    public static function getCurrentLocale(): string
    {
        $settingGeneral = new SettingGeneral();
        return $settingGeneral->site_language;
    }
}
