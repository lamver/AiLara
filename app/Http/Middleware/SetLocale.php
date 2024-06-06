<?php

namespace App\Http\Middleware;

use App\Services\Translation\Translation;
use App\Settings\SettingGeneral;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next)
    {

        $siteLanguage = $this->getLocale();

        $langs = Translation::getLanguages();
        View::share(['languages' => $langs]);

        $firstSegment = request()->segment(1);

        if (in_array($firstSegment, $langs)) {
            $this->setLocale($firstSegment);
        }

        if (config('app.locale') === $siteLanguage) {
            $siteLanguage = "";
        }

        if (!str_starts_with($request->path(), $siteLanguage)) {
            $pathRedirectTo = $this->localizedUrl($request->path(), $siteLanguage);

            return redirect($pathRedirectTo);
        }

        return $next($request);
    }

    /**
     * Generates a localized URL for the given path.
     *
     * @param string $path
     * @param $prefix
     * @return string
     */
    private function localizedUrl(string $path, $prefix): string
    {
        return url(trim($prefix . '/' . $path, '/'));
    }

    /**
     * Retrieves the locale of the Laravel application.
     *
     * @return string The locale of the application.
     */
    private function getLocale(): string
    {
        $settingGeneral = new SettingGeneral();
        return $settingGeneral->site_language;
    }

    /**
     * Sets the locale of the Laravel application.
     *
     * @param string $locale The desired locale to be set.
     * @return void
     */
    private function setLocale(string $locale): void
    {
        request()->session()->put('locale', $locale);
        app()->setLocale($locale);
    }


}
