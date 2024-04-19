<?php

namespace App\Services\Translation;

use App;
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
        return $transistor->getLocales();
    }

    public static function getLinkLocale()
    {
        $transistor = App::make(Manager::class);
        $local = $transistor->getLocales();
        dd($local);
        return "4444";
    }
}
