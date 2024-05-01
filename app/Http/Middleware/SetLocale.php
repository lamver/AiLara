<?php

namespace App\Http\Middleware;

use App\Services\Translation\Translation;
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

        $routeLocale = Translation::checkRoutePrefix();

        $langs = Translation::getLanguages();
        View::share(['languages' => $langs]);
        $this->setLocale($routeLocale ?? config('app.locale'));
        if (!!$routeLocale && $this->getLocale() !== $routeLocale) {
            $this->setLocale($routeLocale);
            return redirect($this->localizedUrl($request->path()));
        }

        return $next($request);
    }

    /**
     * Generates a localized URL for the given path.
     *
     * @param string $path
     * @return string
     */
    private function localizedUrl(string $path): string
    {
        $locale = $this->getLocale();
        $newPath = $path;

        if (request()->segment(1) !== $locale && $locale !== config('app.locale')) {
            $newPath = $locale . '/' . $path;
        }

        return url(trim($newPath, '/'));

    }

    /**
     * Retrieves the locale of the Laravel application.
     *
     * @return string The locale of the application.
     */
    private function getLocale(): string
    {
        if (request()->session()->has('locale')) {
            $locale = request()->session()->get('locale');
        } else {
            $locale = config('app.locale');
        }

        $this->setLocale($locale);
        return $locale;

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
