<?php

namespace App\Http\Middleware;

use App\Services\Translation\Translation;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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

        if ($request->session()->has('locale')) {
            $this->setLocale($request->session()->get('locale'));
        }

        return $next($request);

    }


    /**
     * Sets the locale of the Laravel application.
     *
     * @param string $locale The desired locale to be set.
     * @return void
     */
    private function setLocale(string $locale): void
    {
        Session::put('locale', $locale);
        app()->setLocale($locale);
    }

}
