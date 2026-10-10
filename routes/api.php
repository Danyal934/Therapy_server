<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\GratitudeController;
use App\Http\Controllers\IosSubscriptionController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\NotificationTimeController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PremiumController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\ReflectionController;
use App\Http\Controllers\StripeConfigController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckSingleDeviceSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::middleware('throttle:10,1')->group(function () {
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/signin', [AuthController::class, 'signin']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);

    // Shop routes
    Route::get('/shop/products', [ShopController::class, 'index']);
    Route::post('/shop/orders', [ShopController::class, 'createOrder']);
    Route::post('/shop/orders/confirm', [ShopController::class, 'confirmOrder']);
});

Route::middleware('throttle:30,1')->group(function () {
    Route::get('/ios/premiums/category', [PremiumController::class, 'iosCategories']);
    Route::get('/ios/premiums/category/{category}', [PremiumController::class, 'iosCategoryWise']);
    Route::get('/ios/reflections', [ReflectionController::class, 'iosIndex']);
    Route::post('/ios/subscription', [IosSubscriptionController::class, 'storeDevice']);
    Route::post('/ios/verify', [IosSubscriptionController::class, 'verify']);
    Route::get('/ios/premiums/{id}/audio', [PremiumController::class, 'audioPublic']);
});

Route::middleware('throttle:120,1')->group(function () {
    Route::get('/premiums/{id}/audio/signed', [PremiumController::class, 'audioSigned'])->name('premiums.audio.signed');
    Route::get('/reflections/{id}/audio/signed', [ReflectionController::class, 'audioSigned'])->name('reflections.audio.signed');
});

// Protected routes
Route::middleware(['auth:sanctum', CheckSingleDeviceSession::class, 'throttle:120,1'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/ios/link', [IosSubscriptionController::class, 'link']);

    Route::post('/plan/save', [PlanController::class, 'savePlan']);
    Route::post('/plan/save-ios', [PlanController::class, 'saveIosPlan']);
    Route::get('/plan/check-expired', [PlanController::class, 'checkExpiredPlans']);

    Route::post('/create-payment-intent', [StripeController::class, 'createPaymentIntent']);
    Route::get('/stripe/config', [StripeConfigController::class, 'show']);
    Route::post('/notification-time', [NotificationTimeController::class, 'updateTime']);

    // CRUD routes
    Route::apiResource('users', UserController::class);
    Route::apiResource('journals', JournalController::class);
    Route::apiResource('gratitudes', GratitudeController::class);
    Route::apiResource('rates', RateController::class);

    // reflection CRUD
    Route::post('/reflections', [ReflectionController::class, 'store']);      // Create
    Route::get('/reflections', [ReflectionController::class, 'index']);       // Read all
    Route::get('/reflections/{id}', [ReflectionController::class, 'show']);   // Read one
    Route::get('/reflections/{id}/audio', [ReflectionController::class, 'audio']);
    Route::put('/reflections/{id}', [ReflectionController::class, 'update']); // Update
    Route::delete('/reflections/{id}', [ReflectionController::class, 'destroy']); // Delete
    Route::put('/premium/{id}/journal', [PremiumController::class, 'updateJournal']);

    // premium voice access
    Route::get('/premiums/category', [PremiumController::class, 'categories']);
    Route::post('/premiums', [PremiumController::class, 'store']);      // Create
    Route::get('/premiums', [PremiumController::class, 'index']);       // Read all
    Route::get('/premiums/{id}', [PremiumController::class, 'show']);   // Read single
    Route::get('/premiums/{id}/audio', [PremiumController::class, 'audio']);
    Route::get('/premiums/category/{category}', [PremiumController::class, 'categoryWise']); // Category wise
    Route::put('/premiums/{id}', [PremiumController::class, 'update']);
    Route::post('/premiums/category/update-image', [PremiumController::class, 'updateCategory']);

    Route::delete('/premiums/{id}', [PremiumController::class, 'destroy']);

    // Block user route
    Route::get('/users/{user}/journals', [JournalController::class, 'getUserJournals']);
    Route::patch('/users/{user}/block', [UserController::class, 'blockUser']);
    Route::patch('/users/{user}/unblock', [UserController::class, 'unblockUser']);

    // check-ins

    Route::get('/checkins', [CheckinController::class, 'index']);
    Route::post('/checkins', [CheckinController::class, 'store']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['auth:sanctum', CheckSingleDeviceSession::class, 'throttle:120,1']);

// Serve profile images
Route::get('/profile-images/{filename}', function ($filename) {
    $filename = basename($filename);
    if (! preg_match('/\A[a-zA-Z0-9._-]+\z/', $filename)) {
        abort(404);
    }

    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    if (! in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'], true)) {
        abort(404);
    }

    $base = storage_path('app/public/profile_images');
    $path = $base.DIRECTORY_SEPARATOR.$filename;
    $realBase = realpath($base);
    $realPath = realpath($path);
    if (! $realBase || ! $realPath) {
        abort(404);
    }
    $realBase = rtrim(str_replace('\\', '/', $realBase), '/');
    $realPath = str_replace('\\', '/', $realPath);
    if (! str_starts_with($realPath, $realBase.'/')) {
        abort(404);
    }

    return response()->file($realPath);
})->name('profile.image');

// In routes/web.php or api.php
Route::get('/reflections/pics/{filename}', function ($filename) {
    $filename = basename($filename);
    if (! preg_match('/\A[a-zA-Z0-9._-]+\z/', $filename)) {
        abort(404);
    }

    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    if (! in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
        abort(404);
    }

    $base = storage_path('app/reflections/pics');
    $path = $base.DIRECTORY_SEPARATOR.$filename;
    $realBase = realpath($base);
    $realPath = realpath($path);
    if (! $realBase || ! $realPath) {
        abort(404);
    }
    $realBase = rtrim(str_replace('\\', '/', $realBase), '/');
    $realPath = str_replace('\\', '/', $realPath);
    if (! str_starts_with($realPath, $realBase.'/')) {
        abort(404);
    }

    return response()->file($realPath);
});

Route::get('/reflections/audio/{filename}', function ($filename) {
    $filename = basename($filename);
    if (! preg_match('/\A[a-zA-Z0-9._-]+\z/', $filename)) {
        abort(404);
    }

    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    if (! in_array($ext, ['mp3', 'wav'], true)) {
        abort(404);
    }

    $base = storage_path('app/reflections/audio');
    $path = $base.DIRECTORY_SEPARATOR.$filename;
    $realBase = realpath($base);
    $realPath = realpath($path);
    if (! $realBase || ! $realPath) {
        abort(404);
    }
    $realBase = rtrim(str_replace('\\', '/', $realBase), '/');
    $realPath = str_replace('\\', '/', $realPath);
    if (! str_starts_with($realPath, $realBase.'/')) {
        abort(404);
    }

    return response()->file($realPath);
});
