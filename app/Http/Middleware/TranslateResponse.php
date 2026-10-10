<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslateResponse
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Only translate JSON responses
        $contentType = $response->headers->get('content-type');
        if (
            ! $response->isSuccessful() ||
            ! $contentType ||
            ! str_contains($contentType, 'application/json')
        ) {
            return $response;
        }

        $lang = $request->get('lang', 'en'); // ?lang=ur etc.
        $data = json_decode($response->getContent(), true);
        if (! $data || $lang === 'en') {
            return $response; // skip if English
        }

        // Initialize translator
        $translator = new GoogleTranslate($lang);

        // ✅ Disable SSL verification only in local/dev
        if (app()->environment('local', 'development')) {
            $translator->setOptions(['verify' => false]);
        }

        // Translate recursively with caching
        $translated = $this->translateArray($data, $translator, $lang);

        $response->setContent(json_encode($translated, JSON_UNESCAPED_UNICODE));

        return $response;
    }

    protected function translateArray($data, $translator, $lang)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->translateArray($value, $translator, $lang);
            } elseif (is_string($value)) {
                $data[$key] = $this->translateWithCache($value, $translator, $lang);
            }
        }

        return $data;
    }

    protected function translateWithCache($text, $translator, $lang)
    {
        if (trim($text) === '') {
            return $text;
        }

        $cacheKey = 'translation_'.md5($lang.'_'.$text);

        return Cache::remember($cacheKey, now()->addDays(7), function () use ($translator, $text) {
            try {
                return $translator->translate($text);
            } catch (\Exception $e) {
                return $text; // fallback if translation fails
            }
        });
    }
}
