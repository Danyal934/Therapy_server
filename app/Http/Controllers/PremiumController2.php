<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Premium;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;

class PremiumController extends Controller
{
    protected array $supportedLocales = ['en', 'es', 'fr', 'de'];

    // CREATE (Admin)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'pic' => 'required|image|mimes:jpg,jpeg,png',
            'audio_file' => 'required|mimes:mp3,wav',
            'category' => 'required|string',
            'premium' => 'boolean'
        ]);

        $picPath = $request->file('pic')->store('premium/pics', 'public');
        $audioPath = $request->file('audio_file')->store('premium/audio', 'public');
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


        $premium = Premium::create([
            'title' => $request->title,
            'pic' => $picPath,
            'audio_file' => $audioPath,
            'category' => $request->category,
            'premium' => $request->premium ?? false
        ]);

        $locale = $this->resolveLocale($request);
        App::setLocale($locale);

        return response()->json([
            'message' => __('messages.premium_created'),
            'data' => $this->transformPremium($premium, $request->user()->premium_access ?? false, $locale)
        ], 201);
    }

    // READ (All for Users)
    public function index(Request $request)
    {
        $userIsPremium = $request->user()->premium_access ?? false;
        $locale = $this->resolveLocale($request);
        App::setLocale($locale);
        $premiums = Premium::all()->map(function ($premium) use ($userIsPremium, $locale) {
            return $this->transformPremium($premium, $userIsPremium, $locale);
        });
        return response()->json($premiums);
    }


    // READ (Single)
    public function show(Request $request, $id)
    {
        $userIsPremium = $request->user()->premium_access ?? false;
        $premium = Premium::findOrFail($id);
        $locale = $this->resolveLocale($request);
        App::setLocale($locale);

        return response()->json($this->transformPremium($premium, $userIsPremium, $locale));
    }

    // CATEGORY WISE LIST
    public function categoryWise(Request $request, $category)
    {
        $userIsPremium = $request->user()->premium_access ?? false;
        $locale = $this->resolveLocale($request);
        App::setLocale($locale);

        $premiums = Premium::where('category', $category)->get()->map(function ($premium) use ($userIsPremium, $locale) {
            return $this->transformPremium($premium, $userIsPremium, $locale);
        });

        return response()->json($premiums);
    }
    
    
    // GET DISTINCT CATEGORIES
    public function categories(Request $request)
    {
        $locale = $this->resolveLocale($request);
        App::setLocale($locale);

        $categories = Premium::select('category')
            ->distinct()
            ->pluck('category')
            ->values();

        $translated = $categories->map(function ($category) use ($locale) {
            return [
                'category' => $this->translatePremiumCategory($category, $locale),
                'category_original' => $category,
            ];
        });

        return response()->json($translated);
    }



    // UPDATE (Admin)
    public function update(Request $request, $id)
    {
        $premium = Premium::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string',
            'pic' => 'sometimes|image|mimes:jpg,jpeg,png',
            'audio_file' => 'sometimes|mimes:mp3,wav',
            'category' => 'sometimes|string',
            'premium' => 'boolean'
        ]);

        if ($request->hasFile('pic')) {
            $premium->pic = $request->file('pic')->store('premium/pics', 'public');
        }

        if ($request->hasFile('audio_file')) {
            $premium->audio_file = $request->file('audio_file')->store('premium/audio', 'public');
        }

        if ($request->filled('title')) {
            $premium->title = $request->title;
        }

        if ($request->filled('category')) {
            $premium->category = $request->category;
        }

        if ($request->filled('premium')) {
            $premium->premium = $request->premium;
        }

        $premium->save();

        $locale = $this->resolveLocale($request);
        App::setLocale($locale);

        return response()->json([
            'message' => __('messages.premium_updated'),
            'data' => $this->transformPremium($premium, $request->user()->premium_access ?? false, $locale)
        ]);
    }

    // DELETE (Admin)
    public function destroy($id)
    {
        $premium = Premium::findOrFail($id);
        $premium->delete();

        return response()->json(['message' => __('messages.premium_deleted')]);
    }

    protected function transformPremium(Premium $premium, bool $userIsPremium, ?string $locale = null): array
    {
        $locale = $locale ?? App::getLocale();

        return [
            'id' => $premium->id,
            'title' => $this->translatePremiumTitle($premium->title, $premium->id, $locale),
            'title_original' => $premium->title,
            'category' => $this->translatePremiumCategory($premium->category, $locale),
            'category_original' => $premium->category,
            'pic' => asset('storage/app/public/' . $premium->pic),
            'audio_file' => (!$premium->premium || $userIsPremium)
                ? asset('storage/app/public/' . $premium->audio_file)
                : null,
            'premium' => $premium->premium,
            'journal_prompts' => $premium->journal_prompts ?? [],
        ];
    }

    protected function translatePremiumTitle(?string $title, int $id, ?string $locale = null): ?string
    {
        if (!$title) {
            return $title;
        }

        $locale = $locale ?: App::getLocale() ?: 'en';

        if ($locale === 'en') {
            return $title;
        }

        $langKey = "premium.title_{$id}";
        if (Lang::has($langKey, $locale)) {
            return Lang::get($langKey, ['original' => $title], $locale);
        }

        $cacheKey = 'premium_title_' . $locale . '_' . md5($title);

        return Cache::remember($cacheKey, now()->addDays(7), function () use ($locale, $title) {
            try {
                $translator = new GoogleTranslate($locale);
                if (app()->environment('local', 'development')) {
                    $translator->setOptions(['verify' => false]);
                }
                $translator->setOptions(['verify' => false]);
                return $translator->translate($title);
            } catch (\Throwable $e) {
                return $title;
            }
        });
    }

    protected function translatePremiumCategory(?string $category, ?string $locale = null): ?string
    {
        if (!$category) {
            return $category;
        }

        $locale = $locale ?: App::getLocale() ?: 'en';

        if ($locale === 'en') {
            return $category;
        }

        $slug = Str::slug($category, '_');
        $langKey = "premium.categories.{$slug}";
        if (Lang::has($langKey, $locale)) {
            return Lang::get($langKey, ['original' => $category], $locale);
        }

        $cacheKey = 'premium_category_' . $locale . '_' . md5($category);

        return Cache::remember($cacheKey, now()->addDays(7), function () use ($locale, $category) {
            try {
                $translator = new GoogleTranslate($locale);
                if (app()->environment('local', 'development')) {
                    $translator->setOptions(['verify' => false]);
                }
                $translator->setOptions(['verify' => false]);
                return $translator->translate($category);
            } catch (\Throwable $e) {
                return $category;
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
}
