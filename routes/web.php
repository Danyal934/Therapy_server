<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPremiumController;
use App\Http\Controllers\Admin\AdminReflectionController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\ArtisanController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/private-therapy', [PageController::class, 'privateTherapy'])->name('private-therapy');
Route::get('/corporate-services', [PageController::class, 'corporateServices'])->name('corporate-services');
Route::get('/mobile-app', [PageController::class, 'app'])->name('app');
Route::get('/shop', [PageController::class, 'shop'])->name('shop');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

if (app()->environment('local')) {
    Route::get('/artisan/{command}', [ArtisanController::class, 'run']);

    Route::get('/clear-cache', function () {
        \Artisan::call('route:clear');
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');

        return 'Cache cleared';
    });
}

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/storage/{path}', function (string $path) {
            if (str_contains($path, '..')) {
                abort(404);
            }

            if (! Storage::disk('public')->exists($path)) {
                abort(404);
            }

            return Storage::disk('public')->response($path);
        })->where('path', '.*')->name('storage');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');

        Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');

        Route::get('/premiums', [AdminPremiumController::class, 'index'])->name('premiums.index');
        Route::get('/premiums/create', [AdminPremiumController::class, 'create'])->name('premiums.create');
        Route::post('/premiums', [AdminPremiumController::class, 'store'])->name('premiums.store');
        Route::get('/premiums/{premium}/edit', [AdminPremiumController::class, 'edit'])->name('premiums.edit');
        Route::put('/premiums/{premium}', [AdminPremiumController::class, 'update'])->name('premiums.update');
        Route::delete('/premiums/{premium}', [AdminPremiumController::class, 'destroy'])->name('premiums.destroy');

        Route::get('/reflections', [AdminReflectionController::class, 'index'])->name('reflections.index');
        Route::get('/reflections/create', [AdminReflectionController::class, 'create'])->name('reflections.create');
        Route::post('/reflections', [AdminReflectionController::class, 'store'])->name('reflections.store');
        Route::get('/reflections/{reflection}/edit', [AdminReflectionController::class, 'edit'])->name('reflections.edit');
        Route::put('/reflections/{reflection}', [AdminReflectionController::class, 'update'])->name('reflections.update');
        Route::delete('/reflections/{reflection}', [AdminReflectionController::class, 'destroy'])->name('reflections.destroy');
    });
});
