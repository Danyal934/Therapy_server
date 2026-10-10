<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;

class TranslateApiResponse
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Only process JSON responses
        if ($response instanceof JsonResponse) {
            $data = $response->getData(true);

            // Automatically translate message fields
            if (isset($data['message']) && is_string($data['message'])) {
                $key = $this->findTranslationKey($data['message']);
                if ($key) {
                    $data['message'] = Lang::get("messages.$key", [], App::getLocale());
                }
            }

            $response->setData($data);
        }

        return $response;
    }

    // Try to find the matching translation key
    private function findTranslationKey($message)
    {
        // Your translation keys
        $keys = array_keys(Lang::get('messages'));

        foreach ($keys as $key) {
            if (strcasecmp(Lang::get("messages.$key", [], 'en'), $message) === 0) {
                return $key;
            }
        }

        return null;
    }
}
