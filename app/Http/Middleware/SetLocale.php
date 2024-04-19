<?php

namespace App\Http\Middleware;

use App;
use App\Services\Translation\Translation;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $array = $request->segments();
        $requestedLocale = end($array);
        $langs = Translation::getLanguages();
        $lang = "";

        if (
            in_array($requestedLocale, Translation::getLanguages())
            && $requestedLocale !== config('app.locale')
        ) {

            $lang = $requestedLocale;
            App::setLocale($requestedLocale);
            Session::put($requestedLocale);

        } else {
            App::setLocale(config('app.locale'));
        }

        View::share(['langLink'=> $lang,'languages' => $langs]);

        return $next($request);
    }

}
