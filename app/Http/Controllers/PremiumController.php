<?php

namespace App\Http\Controllers;

use App\Helpers\PremiumAccess;
use App\Models\Category;
use App\Models\Premium;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class PremiumController extends Controller
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

    protected function resolvePremiumAccess(Request $request): bool
    {
        return PremiumAccess::userHasPremium($request->user());
    }

    public function iosCategories(Request $request)
    {
        $deviceId = $request->query('device_id');
        if (! is_string($deviceId) || $deviceId === '') {
            return response()->json([
                'success' => false,
                'message' => __('messages.validation_failed'),
                'errors' => ['device_id' => ['The device_id field is required.']],
            ], 422);
        }

        if (! PremiumAccess::deviceHasPremium($deviceId)) {
            return response()->json([
                'success' => false,
                'message' => "don't have access",
            ], 403);
        }

        $premiumCategories = Premium::select('category')
            ->distinct()
            ->pluck('category');

        foreach ($premiumCategories as $catName) {
            Category::firstOrCreate(
                ['name' => $catName],
                ['active' => true]
            );
        }

        $categories = Category::where('active', true)->get();

        $data = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'category' => $category->name,
                'category_original' => $category->name,
                'sub_heading' => $category->sub_heading,
                'pic' => $category->pic ? asset('storage/app/public/'.$category->pic) : null,
                'audio_file' => $category->audio_file ? asset('storage/app/public/'.$category->audio_file) : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    public function iosCategoryWise(Request $request, $category)
    {
        $deviceId = $request->query('device_id');
        if (! is_string($deviceId) || $deviceId === '') {
            return response()->json([
                'success' => false,
                'message' => __('messages.validation_failed'),
                'errors' => ['device_id' => ['The device_id field is required.']],
            ], 422);
        }

        if (! PremiumAccess::deviceHasPremium($deviceId)) {
            return response()->json([
                'success' => false,
                'message' => "don't have access",
            ], 403);
        }

        $userIsPremium = true;

        $premiums = Premium::where('category', $category)->get()->map(function ($premium) use ($userIsPremium, $deviceId) {
            return [
                'id' => $premium->id,
                'title' => $premium->title,
                'category' => $premium->category,
                'pic' => asset('storage/app/public/'.$premium->pic),
                'audio_file' => $this->buildAudioUrl($premium, $userIsPremium, null, true, $deviceId),
                'premium' => $premium->premium,
                'journal_prompts' => $premium->journal_prompts ?? [],
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $premiums,
        ], 200);
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
            'pic' => 'required|image|mimes:jpg,jpeg,png',
            'audio_file' => 'required|mimes:mp3,wav',
            'category' => 'required|string',
            'premium' => 'boolean',
        ]);

        $picPath = $request->file('pic')->store('premium/pics', 'public');
        $isPremium = (bool) ($request->premium ?? false);
        $audioDisk = $isPremium ? 'local' : 'public';
        $audioPath = $request->file('audio_file')->store('premium/audio', $audioDisk);
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
            'premium' => $request->premium ?? false,
        ]);

        return response()->json(['message' => 'Premium audio created', 'data' => $premium], 201);
    }

    // READ (All for Users)
    public function index(Request $request)
    {
        $userIsPremium = $this->resolvePremiumAccess($request);
        $premiums = Premium::all()->map(function ($premium) use ($userIsPremium) {
            $actorId = request()->user()?->id;

            return ['id' => $premium->id, 'title' => $premium->title, 'category' => $premium->category, 'pic' => asset('storage/app/public/'.$premium->pic), 'audio_file' => $this->buildAudioUrl($premium, $userIsPremium, $actorId, false, null), 'premium' => $premium->premium];
        });

        return response()->json($premiums);
    }

    // READ (Single)
    public function show(Request $request, $id)
    {
        $userIsPremium = $this->resolvePremiumAccess($request);
        $userId = $request->user()?->id;
        $premium = Premium::findOrFail($id);

        return response()->json([
            'id' => $premium->id,
            'title' => $premium->title,
            'category' => $premium->category,
            'pic' => asset('storage/app/public/'.$premium->pic),
            'audio_file' => $this->buildAudioUrl($premium, $userIsPremium, $userId, false, null),
            'premium' => $premium->premium,
        ]);
    }

    // CATEGORY WISE LIST
    public function categoryWise(Request $request, $category)
    {
        $userIsPremium = $this->resolvePremiumAccess($request);

        $premiums = Premium::where('category', $category)->get()->map(function ($premium) use ($userIsPremium) {
            $actorId = request()->user()?->id;

            return [
                'id' => $premium->id,
                'title' => $premium->title,
                'category' => $premium->category,
                'pic' => asset('storage/app/public/'.$premium->pic),
                'audio_file' => $this->buildAudioUrl($premium, $userIsPremium, $actorId, false, null),
                'premium' => $premium->premium,
                'journal_prompts' => $premium->journal_prompts ?? [],
            ];
        });

        // return response()->json($premiums);

        return response()->json([
            'success' => true,
            'data' => $premiums,
        ], 200);
    }

    // GET DISTINCT CATEGORIES
    // GET DISTINCT CATEGORIES
    public function categories(Request $request)
    {
        $userPremiumStatus = PremiumAccess::getUserPremiumStatus($request->user());
        if (! $userPremiumStatus['has_access']) {
            $message = $userPremiumStatus['plan_expired'] 
                ? "Your plan has expired. Please renew to continue accessing premium content." 
                : "You don't have a premium plan yet. Please purchase a plan to access premium content.";
            
            return response()->json([
                'success' => false,
                'message' => $message,
                'plan_expired' => $userPremiumStatus['plan_expired'],
                'had_plan' => $userPremiumStatus['had_plan'],
            ], 403);
        }

        // 1. Get all distinct categories from premiums table
        $premiumCategories = Premium::select('category')
            ->distinct()
            ->pluck('category');

        // 2. Sync with categories table
        foreach ($premiumCategories as $catName) {
            Category::firstOrCreate(
                ['name' => $catName],
                ['active' => true]
            );
        }

        // 3. Fetch from categories table
        $categories = Category::where('active', true)->get();

        $data = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'category' => $category->name,
                'category_original' => $category->name,
                'sub_heading' => $category->sub_heading,
                'pic' => $category->pic ? asset('storage/app/public/'.$category->pic) : null,
                'audio_file' => $category->audio_file ? asset('storage/app/public/'.$category->audio_file) : null,
            ];
        });

        // return response()->json($data);
        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    // UPDATE CATEGORY IMAGE (Admin)
    public function updateCategory(Request $request)
    {
        $denied = $this->requireAdmin($request);
        if ($denied) {
            return $denied;
        }

        $request->validate([
            'category' => 'required|string',
            'pic' => 'nullable|image|mimes:jpg,jpeg,png',
            'audio_file' => 'nullable|mimes:mp3,wav',
        ]);

        $category = Category::where('name', $request->category)->firstOrFail();

        if ($request->hasFile('pic')) {
            $category->pic = $request->file('pic')->store('categories', 'public');
        }

        if ($request->hasFile('audio_file')) {
            $category->audio_file = $request->file('audio_file')->store('categories/audio', 'public');
        }

        if (! $request->hasFile('pic') && ! $request->hasFile('audio_file')) {
            return response()->json([
                'message' => __('messages.validation_failed'),
                'errors' => [
                    'pic' => ['The pic or audio_file field is required.'],
                ],
            ], 422);
        }

        $category->save();

        return response()->json([
            'message' => 'Category image updated successfully',
            'data' => [
                'category' => $category->name,
                'sub_heading' => $category->sub_heading,
                'pic' => asset('storage/app/public/'.$category->pic),
                'audio_file' => $category->audio_file ? asset('storage/app/public/'.$category->audio_file) : null,
            ],
        ]);
    }

    // UPDATE (Admin)
    public function update(Request $request, $id)
    {
        $denied = $this->requireAdmin($request);
        if ($denied) {
            return $denied;
        }

        $premium = Premium::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string',
            'pic' => 'sometimes|image|mimes:jpg,jpeg,png',
            'audio_file' => 'sometimes|mimes:mp3,wav',
            'category' => 'sometimes|string',
            'premium' => 'boolean',
        ]);

        if ($request->hasFile('pic')) {
            $premium->pic = $request->file('pic')->store('premium/pics', 'public');
        }

        if ($request->hasFile('audio_file')) {
            $willBePremium = $request->filled('premium') ? (bool) $request->premium : (bool) $premium->premium;
            $audioDisk = $willBePremium ? 'local' : 'public';
            $premium->audio_file = $request->file('audio_file')->store('premium/audio', $audioDisk);
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

        return response()->json(['message' => 'Premium audio updated', 'data' => $premium]);
    }

    // UPDATE JOURNAL PROMPTS (Admin)
    public function updateJournal(Request $request, $id)
    {
        $denied = $this->requireAdmin($request);
        if ($denied) {
            return $denied;
        }

        $request->validate([
            'journal_prompts' => 'required|array',
            'journal_prompts.*' => 'required|string',
        ]);

        $premium = Premium::findOrFail($id);

        $premium->journal_prompts = $request->journal_prompts;
        $premium->save();

        return response()->json([
            'message' => 'Journal prompts updated successfully',
            'data' => $premium,
        ]);
    }

    // DELETE (Admin)
    public function destroy($id)
    {
        $denied = $this->requireAdmin(request());
        if ($denied) {
            return $denied;
        }

        $premium = Premium::findOrFail($id);
        $premium->delete();

        return response()->json(['message' => 'Premium audio deleted']);
    }

    public function audio(Request $request, $id)
    {
        $premium = Premium::findOrFail($id);
        if (! $premium->premium) {
            return $this->streamFile('public', $premium->audio_file);
        }

        $isPremium = $this->resolvePremiumAccess($request);
        if (! $isPremium) {
            return response()->json(['message' => __('messages.unauthorized')], 403);
        }

        return $this->streamFile('local', $premium->audio_file);
    }

    public function audioPublic(Request $request, $id)
    {
        $premium = Premium::findOrFail($id);
        if (! $premium->premium) {
            return $this->streamFile('public', $premium->audio_file);
        }

        $deviceId = $request->query('device_id');
        if (! \App\Helpers\PremiumAccess::deviceHasPremium(is_string($deviceId) ? $deviceId : null)) {
            return response()->json(['message' => __('messages.unauthorized')], 403);
        }

        return $this->streamFile('local', $premium->audio_file);
    }

    protected function buildAudioUrl(Premium $premium, bool $userIsPremium, ?int $userId, bool $publicContext, ?string $deviceId): ?string
    {
        if (! $premium->premium) {
            return asset('storage/app/public/'.$premium->audio_file);
        }

        if (! $userIsPremium) {
            return null;
        }

        if ($publicContext) {
            if (! is_string($deviceId) || $deviceId === '') {
                return null;
            }

            return URL::temporarySignedRoute('premiums.audio.signed', now()->addMinutes(30), [
                'id' => $premium->id,
                'device_id' => $deviceId,
            ]);
        }

        if (! is_int($userId)) {
            return null;
        }

        return URL::temporarySignedRoute('premiums.audio.signed', now()->addMinutes(30), [
            'id' => $premium->id,
            'uid' => $userId,
        ]);
    }

    public function audioSigned(Request $request, $id)
    {
        if (! $request->hasValidSignature()) {
            return response()->json(['message' => __('messages.unauthorized')], 403);
        }

        $premium = Premium::findOrFail($id);
        if (! $premium->premium) {
            return $this->streamFile('public', $premium->audio_file);
        }

        $uid = $request->query('uid');
        $deviceId = $request->query('device_id');

        if (is_string($uid) && ctype_digit($uid)) {
            $user = User::find((int) $uid);
            if (! PremiumAccess::userHasPremium($user)) {
                return response()->json(['message' => __('messages.unauthorized')], 403);
            }
        } elseif (is_string($deviceId) && PremiumAccess::deviceHasPremium($deviceId)) {
        } else {
            return response()->json(['message' => __('messages.unauthorized')], 403);
        }

        return $this->streamFile('local', $premium->audio_file);
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
