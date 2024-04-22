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
}
