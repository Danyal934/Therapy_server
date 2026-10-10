<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ReflectionController;
use App\Http\Controllers\PremiumController;
use App\Http\Controllers\GratitudeController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\NotificationTimeController;
use Illuminate\Support\Facades\App as AppFacade;
use Illuminate\Support\Facades\Lang;




Route::middleware('api')->group(function () {
    Route::post('/plan/save', [PlanController::class, 'savePlan']);
    Route::get('/plan/check-expired', [PlanController::class, 'checkExpiredPlans']);
    Route::post('/create-payment-intent', [StripeController::class, 'createPaymentIntent']);

    // Public routes auth working
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/signin', [AuthController::class, 'signin']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

// Temporary debug endpoint
Route::get('/debug/locale', function (Request $request) {
    return response()->json([
        'resolved_locale' => AppFacade::getLocale(),
        'header_Accept_Language' => $request->header('Accept-Language'),
        'header_Content_Language' => $request->header('Content-Language'),
        'header_X_Locale' => $request->header('X-Locale'),
        'query_lang' => $request->query('lang'),
        'sample_message' => __('messages.validation_failed'),
        'sample_validation_unique' => Lang::get('validation.unique', ['attribute' => Lang::get('validation.attributes.email', [], AppFacade::getLocale())], AppFacade::getLocale()),
    ]);
})->middleware('api');

// Protected routes
Route::middleware('api')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);

        //notification time
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
        Route::put('/reflections/{id}', [ReflectionController::class, 'update']); // Update
        Route::delete('/reflections/{id}', [ReflectionController::class, 'destroy']); // Delete

        //premium voice access
        Route::get('/premiums/category', [PremiumController::class, 'categories']);
        Route::post('/premiums', [PremiumController::class, 'store']);      // Create
        Route::get('/premiums', [PremiumController::class, 'index']);       // Read all
        Route::get('/premiums/{id}', [PremiumController::class, 'show']);   // Read single
        Route::get('/premiums/category/{category}', [PremiumController::class, 'categoryWise']); // Category wise
        Route::put('/premiums/{id}', [PremiumController::class, 'update']);

        Route::delete('/premiums/{id}', [PremiumController::class, 'destroy']);


        // Block user route
        Route::get('/users/{user}/journals', [JournalController::class, 'getUserJournals']);
        Route::patch('/users/{user}/block', [UserController::class, 'blockUser']);
        Route::patch('/users/{user}/unblock', [UserController::class, 'unblockUser']);



        //check-ins

        Route::get('/checkins', [CheckinController::class, 'index']);
        Route::post('/checkins', [CheckinController::class, 'store']);
    });
});









Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




// Serve profile images
Route::get('/profile-images/{filename}', function ($filename) {
    $path = storage_path('app/public/profile_images/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
})->name('profile.image');

// In routes/web.php or api.php
Route::get('/reflections/pics/{filename}', function ($filename) {
    $path = storage_path('app/reflections/pics/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
});

Route::get('/reflections/audio/{filename}', function ($filename) {
    $path = storage_path('app/reflections/audio/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
});
