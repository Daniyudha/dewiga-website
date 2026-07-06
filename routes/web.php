<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Auth::routes(['register' => false]);

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {

    // Admin routes - no language prefix
    Route::group(['middleware' => ['is_admin','auth'], 'prefix' => 'admin', 'as' => 'admin.'], function() {
        Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class)->only(['index', 'destroy']);
        Route::resource('travel_packages', \App\Http\Controllers\Admin\TravelPackageController::class)->except('show');
        Route::resource('travel_packages.galleries', \App\Http\Controllers\Admin\GalleryController::class)->except(['create', 'index','show']);
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except('show');
        Route::resource('blogs', \App\Http\Controllers\Admin\BlogController::class)->except('show');
        Route::resource('testimonials', \App\Http\Controllers\Admin\TestimonialController::class)->only(['index', 'destroy']);
        Route::patch('testimonials/{testimonial}/toggle', [\App\Http\Controllers\Admin\TestimonialController::class, 'toggle'])->name('testimonials.toggle');
        Route::get('users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
        Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
        Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

        // CKEditor image upload
        Route::post('upload-image', [\App\Http\Controllers\Admin\UploadController::class, 'image'])->name('upload.image');
    });

    // Frontend routes with language prefix
    Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('homepage');

    // travel packages
    Route::get('travel-packages', [\App\Http\Controllers\TravelPackageController::class, 'index'])->name('travel_package.index');
    Route::get('travel-packages/{travel_package:slug}', [\App\Http\Controllers\TravelPackageController::class, 'show'])->name('travel_package.show');

    // blogs
    Route::get('blogs', [\App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
    Route::get('blogs/{blog:slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

    // gallery
    Route::get('gallery', function() { return view('gallery'); })->name('gallery');
    // contact
    Route::get('contact', function() { return view('contact'); })->name('contact');
    // about-us
    Route::get('about-us', function() { return view('about-us'); })->name('about-us');
    // homestay
    Route::get('homestay', function() { return view('homestay'); })->name('homestay');
    // community impact
    Route::get('community-impact', function() { return view('impact'); })->name('impact');
    // booking
    Route::post('booking', [App\Http\Controllers\BookingController::class, 'store'])->name('booking.store');
    Route::post('send-email', [App\Http\Controllers\ContactController::class, 'sendEmail'])->name('send.email');

    // testimonials
    Route::get('testimonials/create', [\App\Http\Controllers\TestimonialController::class, 'create'])->name('testimonials.create');
    Route::post('testimonials', [\App\Http\Controllers\TestimonialController::class, 'store'])->name('testimonials.store');

    // sitemap
    Route::get('sitemap.xml', [\App\Http\Controllers\HomeController::class, 'sitemap'])->name('sitemap');
});

