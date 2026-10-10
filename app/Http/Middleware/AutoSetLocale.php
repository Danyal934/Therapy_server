<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class AutoSetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // ✅ Supported language codes
        $supportedLocales = ['en', 'es', 'fr', 'de']; // English, Spanish, French, German

        // ✅ Default language
        $locale = 'en';

        // ✅ Priority: query param > Accept-Language header > X-Locale/Content-Language > user profile > default
        if ($request->query('lang') && in_array($request->query('lang'), $supportedLocales)) {
            $locale = $request->query('lang');
        } elseif ($request->hasHeader('Accept-Language')) {
            // Parse Accept-Language robustly: handle values like 'es-ES', 'es-ES,es;q=0.9', '-es', etc.
            $raw = $request->header('Accept-Language');
            $candidates = explode(',', $raw);
            foreach ($candidates as $cand) {
                // extract first 2 alpha letters as language code
                if (preg_match('/([a-zA-Z]{2})/', $cand, $m)) {
                    $code = strtolower($m[1]);
                    if (in_array($code, $supportedLocales)) {
                        $locale = $code;
                        break;
                    }
                }
            }
        } elseif ($request->hasHeader('Content-Language')) {
            $raw = $request->header('Content-Language');
            if (preg_match('/([a-zA-Z]{2})/', $raw, $m)) {
                $code = strtolower($m[1]);
                if (in_array($code, $supportedLocales)) {
                    $locale = $code;
                }
            }
        } elseif ($request->hasHeader('X-Locale')) {
            $headerLocale = strtolower(substr(preg_replace('/[^a-zA-Z]/', '', $request->header('X-Locale')), 0, 2));
            if (in_array($headerLocale, $supportedLocales)) {
                $locale = $headerLocale;
            }
        } elseif (Auth::check() && Auth::user()->language && in_array(Auth::user()->language, $supportedLocales)) {
            $locale = Auth::user()->language;
        }

        // ✅ Set language for this request (app + config)
        App::setLocale($locale);
        config(['app.locale' => $locale]);

        $response = $next($request);
        if (method_exists($response, 'header')) {
            $response->header('X-App-Locale', $locale);
        }
        return $response;
    }
}
