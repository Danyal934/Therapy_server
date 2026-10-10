<?php

namespace App\Http\Controllers;

use App\Helpers\PremiumAccess;
use App\Models\Reflection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ReflectionController extends Controller
{
    protected function requireAdmin(Request $request)
    {
        $user = $request->user();
        if (! ($user?->admin ?? false)) {
            return response()->json([
                'message' => __('messages.unauthorized_admin'),
            ], 403);
        }

        return null;
    }

    // CREATE (Admin)
    public function store(Request $request)
    {
        $denied = $this->requireAdmin($request);
        if ($denied) {
            return $denied;
        }

        $request->validate([
            'title' => 'required|string',
            'subheadings' => ['sometimes', function (string $attribute, mixed $value, \Closure $fail) {
                if (! is_string($value) && ! is_array($value)) {
                    $fail("The {$attribute} must be a string or an array.");
                }

                if (is_array($value)) {
                    foreach ($value as $item) {
                        if (! is_string($item)) {
                            $fail("The {$attribute} items must be strings.");
                            break;
                        }
                    }
                }
            }],
            'pic' => 'required|image|mimes:jpg,jpeg,png',
            'audio_file' => 'required|mimes:mp3,wav',
            'premium' => 'boolean',
        ]);

        $picPath = $request->file('pic')->store('reflections/pics', 'public');
        $isPremium = (bool) ($request->premium ?? false);
        $audioDisk = $isPremium ? 'local' : 'public';
        $audioPath = $request->file('audio_file')->store('reflections/audio', $audioDisk);
        // if ($request->hasFile('pic')) {
        //     $picName = time() . '_' . uniqid() . '.' . $request->file('pic')->getClientOriginalExtension();
        //     $request->file('pic')->move(public_path('uploads/pics'), $picName);
        //     $picPath = 'uploads/pics/' . $picName;
        // }

        // if ($request->hasFile('audio_file')) {
        //     $audioName = time() . '_' . uniqid() . '.' . $request->file('audio_file')->getClientOriginalExtension();
        //     $request->file('audio_file')->move(public_path('uploads/audio'), $audioName);
        //     $audioPath = 'uploads/audio/' . $audioName;
        // }

        $reflection = Reflection::create([
            'title' => $request->title,
            'pic' => $picPath,
            'audio_file' => $audioPath,
            'premium' => $request->premium ?? false,
        ]);

        if ($request->has('subheadings')) {
            $reflection->subheadings = $this->normalizeStringArray($request->input('subheadings'));
            $reflection->save();
        }

        $locale = $this->resolveLocale($request);
        App::setLocale($locale);

        $userIsPremium = PremiumAccess::userHasPremium($request->user());

        return response()->json([
            'message' => __('messages.reflection_created'),
            'data' => $this->transformReflection($reflection, $userIsPremium, $locale),
        ], 201);
    }

    // READ (All for Users)
    public function index(Request $request)
    {
        $userIsPremium = PremiumAccess::userHasPremium($request->user());

        $locale = $this->resolveLocale($request);
        App::setLocale($locale);

        $reflections = Reflection::all()->map(function ($reflection) use ($userIsPremium, $locale) {
            return $this->transformReflection($reflection, $userIsPremium, $locale);
        });

        return response()->json($reflections);
    }

    public function iosIndex(Request $request)
    {
        $userIsPremium = false;

        $locale = $this->resolveLocale($request);
        App::setLocale($locale);

        $reflections = Reflection::all()->map(function ($reflection) use ($userIsPremium, $locale) {
            return $this->transformReflection($reflection, $userIsPremium, $locale);
        });

        return response()->json($reflections);
    }

    // READ (Single)
    public function show(Request $request, $id)
    {
        $userIsPremium = PremiumAccess::userHasPremium($request->user());
        $reflection = Reflection::findOrFail($id);

        $locale = $this->resolveLocale($request);
        App::setLocale($locale);

        return response()->json($this->transformReflection($reflection, $userIsPremium, $locale));
    }

    // UPDATE (Admin)
    public function update(Request $request, $id)
    {
        $denied = $this->requireAdmin($request);
        if ($denied) {
            return $denied;
        }

        $reflection = Reflection::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string',
            'subheadings' => ['sometimes', function (string $attribute, mixed $value, \Closure $fail) {
                if (! is_string($value) && ! is_array($value)) {
                    $fail("The {$attribute} must be a string or an array.");
                }

                if (is_array($value)) {
                    foreach ($value as $item) {
                        if (! is_string($item)) {
                            $fail("The {$attribute} items must be strings.");
                            break;
                        }
                    }
                }
            }],
            'pic' => 'sometimes|image|mimes:jpg,jpeg,png',
            'audio_file' => 'sometimes|mimes:mp3,wav',
            'premium' => 'boolean',
        ]);

        if ($request->hasFile('pic')) {
            $reflection->pic = $request->file('pic')->store('reflections/pics', 'public');
        }

        if ($request->hasFile('audio_file')) {
            $isPremium = (bool) ($request->filled('premium') ? $request->premium : $reflection->premium);
            $audioDisk = $isPremium ? 'local' : 'public';
            $reflection->audio_file = $request->file('audio_file')->store('reflections/audio', $audioDisk);
        }

        if ($request->filled('title')) {
            $reflection->title = $request->title;
        }

        if ($request->has('subheadings')) {
            $reflection->subheadings = $this->normalizeStringArray($request->input('subheadings'));
        }

        if ($request->filled('premium')) {
            $reflection->premium = $request->premium;
        }

        $reflection->save();

        $locale = $this->resolveLocale($request);
        App::setLocale($locale);
        $userIsPremium = PremiumAccess::userHasPremium($request->user());

        return response()->json([
            'message' => __('messages.reflection_updated'),
            'data' => $this->transformReflection($reflection, $userIsPremium, $locale),
        ]);
    }

    // DELETE (Admin)
    public function destroy($id)
    {
        $denied = $this->requireAdmin(request());
        if ($denied) {
            return $denied;
        }

        $reflection = Reflection::findOrFail($id);
        $reflection->delete();

        return response()->json(['message' => __('messages.reflection_deleted')]);
    }

    protected array $supportedLocales = ['en', 'es', 'fr', 'de'];

    protected function transformReflection(Reflection $reflection, bool $userIsPremium, ?string $locale = null): array
    {
        $locale = $locale ?? App::getLocale();

        $audioUrl = null;
        if (! $reflection->premium) {
            $audioUrl = asset('storage/app/public/'.$reflection->audio_file);
        } elseif ($userIsPremium) {
            $uid = request()->user()?->id;
            if (is_int($uid)) {
                $audioUrl = URL::temporarySignedRoute('reflections.audio.signed', now()->addMinutes(30), [
                    'id' => $reflection->id,
                    'uid' => $uid,
                ]);
            }
        }

        return [
            'id' => $reflection->id,
            'title' => $this->translateReflectionTitle($reflection, $locale),
            'subheadings' => $reflection->subheadings ?? [],
            'pic' => asset('storage/app/public/'.$reflection->pic),
            'audio_file' => $audioUrl,
            'premium' => $reflection->premium,
            'journal_prompts' => $reflection->journal_prompts ?? [],
        ];
    }

    protected function normalizeStringArray(mixed $value): array
    {
        if (is_array($value)) {
            return array_values(array_filter(array_map(function ($item) {
                if (! is_string($item)) {
                    return null;
                }

                $trimmed = trim($item);

                return $trimmed === '' ? null : $trimmed;
            }, $value)));
        }

        if (is_string($value)) {
            return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $value))));
        }

        return [];
    }

    protected function translateReflectionTitle(Reflection $reflection, ?string $locale = null): ?string
    {
        $title = $reflection->title;
        if (! $title) {
            return $title;
        }

        $locale = $locale ?: App::getLocale() ?: 'en';
        if ($locale === 'en') {
            return $title;
        }

        $langKey = "reflections.title_{$reflection->id}";
        if (Lang::has($langKey, $locale)) {
            return Lang::get($langKey, ['original' => $title], $locale);
        }

        $cacheKey = 'reflection_title_'.$locale.'_'.md5($title);

        return Cache::remember($cacheKey, now()->addDays(7), function () use ($locale, $title) {
            try {
                $translator = new GoogleTranslate($locale);
                if (app()->environment('local', 'development')) {
                    $translator->setOptions(['verify' => false]);
                }

                return $translator->translate($title);
            } catch (\Throwable $e) {
                return $title;
            }
        });
    }

    protected function resolveLocale(Request $request): string
    {
        $locale = App::getLocale() ?: 'en';

        if ($request->hasHeader('Accept-Language')) {
            $raw = $request->header('Accept-Language');
            $candidates = explode(',', $raw);
            foreach ($candidates as $candidate) {
                if (preg_match('/([a-zA-Z]{2})/', $candidate, $matches)) {
                    $code = strtolower($matches[1]);
                    if (in_array($code, $this->supportedLocales, true)) {
                        return $code;
                    }
                }
            }
        }

        return $locale;
    }

    public function audio(Request $request, $id)
    {
        $reflection = Reflection::findOrFail($id);
        if (! $reflection->premium) {
            return $this->streamFile('public', $reflection->audio_file);
        }

        $isPremium = PremiumAccess::userHasPremium($request->user());
        if (! $isPremium) {
            return response()->json(['message' => __('messages.unauthorized')], 403);
        }

        return $this->streamFile('local', $reflection->audio_file);
    }

    public function audioSigned(Request $request, $id)
    {
        if (! $request->hasValidSignature()) {
            return response()->json(['message' => __('messages.unauthorized')], 403);
        }

        $reflection = Reflection::findOrFail($id);
        if (! $reflection->premium) {
            return $this->streamFile('public', $reflection->audio_file);
        }

        $uid = $request->query('uid');
        if (! is_string($uid) || ! ctype_digit($uid)) {
            return response()->json(['message' => __('messages.unauthorized')], 403);
        }

        $user = \App\Models\User::find((int) $uid);
        if (! PremiumAccess::userHasPremium($user)) {
            return response()->json(['message' => __('messages.unauthorized')], 403);
        }

        return $this->streamFile('local', $reflection->audio_file);
    }

    protected function streamFile(string $disk, string $relativePath)
    {
        $relativePath = $this->normalizeRelativePath($relativePath);
        if (preg_match('#(^|/)\.\.(?:/|$)#', $relativePath)) {
            abort(404);
        }

        if (! Storage::disk($disk)->exists($relativePath)) {
            if ($disk === 'local' && Storage::disk('public')->exists($relativePath)) {
                return response()->file(Storage::disk('public')->path($relativePath));
            }

            $publicStoragePath = $this->publicStoragePath($relativePath);
            if (is_string($publicStoragePath)) {
                return response()->file($publicStoragePath);
            }

            abort(404);
        }

        return response()->file(Storage::disk($disk)->path($relativePath));
    }

    protected function normalizeRelativePath(string $path): string
    {
        $path = ltrim($path, '/\\');
        $path = str_replace('\\', '/', $path);
        $path = preg_replace('#\Astorage/app/public/#', '', $path) ?? $path;
        $path = preg_replace('#\Apublic/#', '', $path) ?? $path;

        return $path;
    }

    protected function publicStoragePath(string $relativePath): ?string
    {
        $candidate = public_path('storage'.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relativePath));
        $realCandidate = realpath($candidate);
        $realBase = realpath(public_path('storage'));

        if (! is_string($realCandidate) || ! is_string($realBase)) {
            return null;
        }

        $realCandidate = str_replace('\\', '/', $realCandidate);
        $realBase = rtrim(str_replace('\\', '/', $realBase), '/');

        if (! str_starts_with($realCandidate, $realBase.'/')) {
            return null;
        }

        if (! is_file($realCandidate)) {
            return null;
        }

        return $realCandidate;
    }
}
