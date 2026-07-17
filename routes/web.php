<?php

use App\Http\Controllers\Admin\AdminArticleController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminFaqController;
use App\Http\Controllers\Admin\AdminInquiryController;
use App\Http\Controllers\Admin\AdminItineraryPackageController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\TransportationController;
use Illuminate\Support\Facades\Route;

// Locale switcher (session-based)
Route::get('/locale/{locale}', [LocaleController::class, 'switch'])
    ->whereIn('locale', ['id', 'en'])
    ->name('locale.switch');

// Halaman publik
Route::get('/', [MapController::class, 'index']);
Route::get('/map', [MapController::class, 'index'])->name('map');
Route::get('/library', [MapController::class, 'library'])->name('library');
Route::get('/place/{place}', [PlaceController::class, 'show'])->name('place.detail');
Route::post('/place/{place}/review', [PlaceController::class, 'storeReview'])->name('place.review.store')->middleware('throttle:review');
Route::get('/api/boundary', [MapController::class, 'apiBoundary']);

// Navigasi & routing
Route::get('/route', function (\Illuminate\Http\Request $request) {
    $query = http_build_query($request->only(['lat', 'lng', 'name']));
    return redirect('/map' . ($query ? '?' . $query : ''));
})->name('route.index');

// Fitur budaya & informasi
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');
Route::get('/contact', [InquiryController::class, 'create'])->name('contact.create');
Route::post('/contact', [InquiryController::class, 'store'])->name('contact.store')->middleware('throttle:contact');
Route::get('/transportation', [TransportationController::class, 'index'])->name('transportation.index');

// Admin auth
Route::get('/gerbang-admin', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/gerbang-admin', [AuthController::class, 'login'])->middleware('throttle:login');
Route::post('/gerbang-keluar', [AuthController::class, 'logout'])->name('admin.logout');

// Admin CRUD
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // Places (existing)
    Route::get('/places', [PlaceController::class, 'adminIndex'])->name('places.index');
    Route::get('/places/create', [PlaceController::class, 'create'])->name('places.create');
    Route::post('/places', [PlaceController::class, 'store'])->name('places.store');
    Route::get('/places/{place}/edit', [PlaceController::class, 'edit'])->name('places.edit');
    Route::put('/places/{place}', [PlaceController::class, 'update'])->name('places.update');
    Route::put('/places/{place}/quick-update', [PlaceController::class, 'quickUpdate'])->name('places.quick-update');
    Route::delete('/places/{place}', [PlaceController::class, 'destroy'])->name('places.destroy');

    // Events
    Route::resource('events', AdminEventController::class)
        ->except('show')
        ->names('events');

    // Articles
    Route::resource('articles', AdminArticleController::class)
        ->except('show')
        ->names('articles');

    // Itinerary Packages
    Route::resource('itinerary-packages', AdminItineraryPackageController::class)
        ->except('show')
        ->names('itinerary-packages');

    // Inquiries
    Route::get('/inquiries', [AdminInquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/inquiries/{inquiry}', [AdminInquiryController::class, 'show'])->name('inquiries.show');
    Route::delete('/inquiries/{inquiry}', [AdminInquiryController::class, 'destroy'])->name('inquiries.destroy');

    // FAQs
    Route::resource('faqs', AdminFaqController::class)
        ->except('show')
        ->names('faqs');

    // Categories
    Route::resource('categories', AdminCategoryController::class)
        ->except('show')
        ->names('categories');
});
