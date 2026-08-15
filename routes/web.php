<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Auth::routes(['register' => false]);

Route::post('api/midtrans/webhook', [\App\Http\Controllers\MidtransWebhookController::class, 'handle'])->name('midtrans.webhook');

// Public estimation view (no login required, for sharing via WhatsApp)
Route::get('estimasi/{estimationNumber}', [\App\Http\Controllers\PublicEstimationController::class, 'show'])->name('public.estimation.show');

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {

    // Admin routes - no language prefix
    Route::group(['middleware' => ['is_admin','auth'], 'prefix' => 'admin', 'as' => 'admin.'], function() {
        Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class)->except('show');
        Route::patch('bookings/{booking}/confirm', [\App\Http\Controllers\Admin\BookingController::class, 'confirm'])->name('bookings.confirm');
        Route::patch('bookings/{booking}/cancel', [\App\Http\Controllers\Admin\BookingController::class, 'cancel'])->name('bookings.cancel');
        Route::resource('travel_packages', \App\Http\Controllers\Admin\TravelPackageController::class)->except('show');
        Route::patch('travel_packages/{travel_package}/toggle-signature', [\App\Http\Controllers\Admin\TravelPackageController::class, 'toggleSignature'])->name('travel_packages.toggle-signature');
        Route::resource('roles', \App\Http\Controllers\Admin\RolePermissionController::class);
        Route::resource('travel_packages.galleries', \App\Http\Controllers\Admin\GalleryController::class)->except(['create', 'index','show']);
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except('show');
        Route::resource('blogs', \App\Http\Controllers\Admin\BlogController::class)->except('show');
        Route::resource('testimonials', \App\Http\Controllers\Admin\TestimonialController::class)->only(['index', 'destroy']);
        Route::patch('testimonials/{testimonial}/toggle', [\App\Http\Controllers\Admin\TestimonialController::class, 'toggle'])->name('testimonials.toggle');
        Route::resource('users', \App\Http\Controllers\UserController::class)->except('show');
        Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
        Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

        // Schedules
        Route::resource('schedules', \App\Http\Controllers\Admin\ScheduleController::class)->except('show');
        Route::get('schedules/{schedule}/show', [\App\Http\Controllers\Admin\ScheduleController::class, 'show'])->name('schedules.show');
        Route::patch('schedules/{schedule}/toggle-active', [\App\Http\Controllers\Admin\ScheduleController::class, 'toggleActive'])->name('schedules.toggle-active');
        Route::patch('schedules/{schedule}/update-status', [\App\Http\Controllers\Admin\ScheduleController::class, 'updateStatus'])->name('schedules.update-status');
        Route::post('schedules/{schedule}/generate-midtrans-link', [\App\Http\Controllers\Admin\ScheduleController::class, 'generateMidtransPaymentLink'])->name('schedules.generate-midtrans-link');
        Route::delete('schedules/{schedule}/payments/{payment}', [\App\Http\Controllers\Admin\ScheduleController::class, 'destroyPayment'])->name('schedules.payments.destroy');

        // Open Trip Registrations
        Route::get('open-trip-registrations/{schedule}/export', [\App\Http\Controllers\Admin\OpenTripRegistrationController::class, 'export'])->name('open-trip-registrations.export');
        Route::get('open-trip-registrations/{schedule}/schedule', [\App\Http\Controllers\Admin\OpenTripRegistrationController::class, 'showSchedule'])->name('open-trip-registrations.schedule');
        Route::post('open-trip-registrations/{schedule}/recalculate', [\App\Http\Controllers\Admin\OpenTripRegistrationController::class, 'recalculate'])->name('open-trip-registrations.recalculate');
        Route::resource('open-trip-registrations', \App\Http\Controllers\Admin\OpenTripRegistrationController::class);
        Route::resource('guests', \App\Http\Controllers\Admin\GuestController::class)->except('show');
        Route::get('visit-reports', [\App\Http\Controllers\Admin\VisitReportController::class, 'index'])->name('visit-reports.index');
        Route::get('visit-reports/export', [\App\Http\Controllers\Admin\VisitReportController::class, 'export'])->name('visit-reports.export');
        Route::resource('transactions', \App\Http\Controllers\Admin\TransactionController::class)->except('show');

        // Partner Logos
        Route::resource('partner_logos', \App\Http\Controllers\Admin\PartnerLogoController::class)->except('show');
        Route::resource('activities', \App\Http\Controllers\Admin\ActivityController::class)->except('show');
        Route::resource('activities.galleries', \App\Http\Controllers\Admin\ActivityGalleryController::class)->except(['create', 'index', 'show']);
        Route::post('activities/reorder', [\App\Http\Controllers\Admin\ActivityController::class, 'reorder'])->name('activities.reorder');
        // Site Gallery
        Route::get('site-galleries', [\App\Http\Controllers\Admin\SiteGalleryController::class, 'index'])->name('site-galleries.index');
        Route::post('site-galleries/upload', [\App\Http\Controllers\Admin\SiteGalleryController::class, 'upload'])->name('site-galleries.upload');
        Route::post('site-galleries/reorder', [\App\Http\Controllers\Admin\SiteGalleryController::class, 'reorder'])->name('site-galleries.reorder');
        Route::post('site-galleries/{siteGallery}/title', [\App\Http\Controllers\Admin\SiteGalleryController::class, 'updateTitle'])->name('site-galleries.update-title');
        Route::delete('site-galleries/{siteGallery}', [\App\Http\Controllers\Admin\SiteGalleryController::class, 'destroy'])->name('site-galleries.destroy');

        Route::post('partner_logos/reorder', [\App\Http\Controllers\Admin\PartnerLogoController::class, 'reorder'])->name('partner_logos.reorder');

        // Hero Settings
        Route::get('hero-settings', [\App\Http\Controllers\Admin\HeroSettingController::class, 'index'])->name('hero-settings.index');
        Route::get('hero-settings/{heroSetting}', [\App\Http\Controllers\Admin\HeroSettingController::class, 'edit'])->name('hero-settings.edit');
        Route::put('hero-settings/{heroSetting}', [\App\Http\Controllers\Admin\HeroSettingController::class, 'update'])->name('hero-settings.update');
        Route::post('hero-settings/{heroSetting}/slides', [\App\Http\Controllers\Admin\HeroSettingController::class, 'uploadSlide'])->name('hero-settings.slides.upload');
        Route::delete('hero-settings/{heroSetting}/slides/{heroSlide}', [\App\Http\Controllers\Admin\HeroSettingController::class, 'deleteSlide'])->name('hero-settings.slides.destroy');
        Route::post('hero-settings/{heroSetting}/slides/reorder', [\App\Http\Controllers\Admin\HeroSettingController::class, 'reorderSlides'])->name('hero-settings.slides.reorder');

        // Price Calculator
        Route::prefix('price-calculator')->name('price-calculator.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\PriceCalculatorController::class, 'index'])->name('index');
            Route::get('create', [\App\Http\Controllers\Admin\PriceCalculatorController::class, 'create'])->name('create');
            Route::post('calculate', [\App\Http\Controllers\Admin\PriceCalculatorController::class, 'calculate'])->name('calculate');
            Route::post('store', [\App\Http\Controllers\Admin\PriceCalculatorController::class, 'store'])->name('store');

            // Settings (before {priceEstimation})
            Route::get('settings/index', [\App\Http\Controllers\Admin\PriceCalculatorController::class, 'settings'])->name('settings');
            Route::put('settings/components/{pricingComponent}', [\App\Http\Controllers\Admin\PriceCalculatorController::class, 'updateComponentPrice'])->name('update-component-price');
            Route::put('settings/addons/{pricingAddon}', [\App\Http\Controllers\Admin\PriceCalculatorController::class, 'updateAddonPrice'])->name('update-addon-price');
            Route::put('settings/tiers/{participantPriceTier}', [\App\Http\Controllers\Admin\PriceCalculatorController::class, 'updateTier'])->name('update-tier');

            // PDF (before {priceEstimation})
            Route::get('{priceEstimation}/pdf-view', [\App\Http\Controllers\Admin\PriceCalculatorController::class, 'pdfView'])->name('pdf-view');
            Route::get('{priceEstimation}/pdf-download', [\App\Http\Controllers\Admin\PriceCalculatorController::class, 'pdfDownload'])->name('pdf-download');

            // Model-bound routes
            Route::get('{priceEstimation}', [\App\Http\Controllers\Admin\PriceCalculatorController::class, 'show'])->name('show');
            Route::get('{priceEstimation}/edit', [\App\Http\Controllers\Admin\PriceCalculatorController::class, 'edit'])->name('edit');
            Route::put('{priceEstimation}', [\App\Http\Controllers\Admin\PriceCalculatorController::class, 'update'])->name('update');
            Route::get('{priceEstimation}/duplicate', [\App\Http\Controllers\Admin\PriceCalculatorController::class, 'duplicate'])->name('duplicate');
            Route::delete('{priceEstimation}', [\App\Http\Controllers\Admin\PriceCalculatorController::class, 'destroy'])->name('destroy');
            Route::post('{priceEstimation}/recalculate', [\App\Http\Controllers\Admin\PriceCalculatorController::class, 'recalculate'])->name('recalculate');
            Route::post('{priceEstimation}/convert-to-schedule', [\App\Http\Controllers\Admin\PriceCalculatorController::class, 'convertToSchedule'])->name('convert-to-schedule');
        });

        // Proposal Program (extends price-calculator)
        Route::resource('proposals', \App\Http\Controllers\Admin\ProposalController::class)->parameters(['proposals' => 'priceEstimation']);
        Route::post('proposals/{priceEstimation}/calculate', [\App\Http\Controllers\Admin\ProposalController::class, 'calculate'])->name('proposals.calculate');
        Route::post('proposals/{priceEstimation}/recalculate', [\App\Http\Controllers\Admin\ProposalController::class, 'recalculate'])->name('proposals.recalculate');
        Route::get('proposals/{priceEstimation}/duplicate', [\App\Http\Controllers\Admin\ProposalController::class, 'duplicate'])->name('proposals.duplicate');
        Route::post('proposals/{priceEstimation}/update-program', [\App\Http\Controllers\Admin\ProposalController::class, 'updateProgram'])->name('proposals.update-program');
        Route::post('proposals/{priceEstimation}/update-facilities', [\App\Http\Controllers\Admin\ProposalController::class, 'updateFacilities'])->name('proposals.update-facilities');
        Route::patch('proposals/{priceEstimation}/update-status', [\App\Http\Controllers\Admin\ProposalController::class, 'updateStatus'])->name('proposals.update-status');
        Route::post('proposals/{priceEstimation}/convert-to-schedule', [\App\Http\Controllers\Admin\ProposalController::class, 'convertToSchedule'])->name('proposals.convert-to-schedule');
        Route::get('proposals/{priceEstimation}/pdf-view', [\App\Http\Controllers\Admin\ProposalController::class, 'pdfView'])->name('proposals.pdf-view');
        Route::get('proposals/{priceEstimation}/pdf-download', [\App\Http\Controllers\Admin\ProposalController::class, 'pdfDownload'])->name('proposals.pdf-download');
        Route::get('proposals/convert-estimation/{priceEstimation}', [\App\Http\Controllers\Admin\ProposalController::class, 'convertEstimationToProposal'])->name('proposals.convert-estimation');
        Route::get('proposals/{priceEstimation}/send-whatsapp', [\App\Http\Controllers\Admin\ProposalController::class, 'sendWhatsApp'])->name('proposals.send-whatsapp');
        Route::get('proposals/settings/index', [\App\Http\Controllers\Admin\ProposalController::class, 'settings'])->name('proposals.settings');
        // Proposal Settings
        Route::get('proposal-settings', [\App\Http\Controllers\Admin\ProposalSettingController::class, 'index'])->name('proposal-settings.index');
        Route::put('proposal-settings', [\App\Http\Controllers\Admin\ProposalSettingController::class, 'update'])->name('proposal-settings.update');

        // Template Rundown
        Route::resource('rundown-templates', \App\Http\Controllers\Admin\RundownTemplateController::class);
        Route::patch('rundown-templates/{rundownTemplate}/toggle-active', [\App\Http\Controllers\Admin\RundownTemplateController::class, 'toggleActive'])->name('rundown-templates.toggle-active');
        Route::post('rundown-templates/{rundownTemplate}/duplicate', [\App\Http\Controllers\Admin\RundownTemplateController::class, 'duplicate'])->name('rundown-templates.duplicate');
        Route::post('rundown-templates/{rundownTemplate}/reorder-items', [\App\Http\Controllers\Admin\RundownTemplateController::class, 'reorderItems'])->name('rundown-templates.reorder-items');

        // Schedule Rundown
        Route::prefix('schedules/{schedule}/rundown')->name('schedules.rundown.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ScheduleRundownController::class, 'show'])->name('show');
            Route::post('create-from-template', [\App\Http\Controllers\Admin\ScheduleRundownController::class, 'createFromTemplate'])->name('create-from-template');
            Route::post('create-empty', [\App\Http\Controllers\Admin\ScheduleRundownController::class, 'createEmpty'])->name('create-empty');
            Route::get('{rundown}/edit', [\App\Http\Controllers\Admin\ScheduleRundownController::class, 'edit'])->name('edit');
            Route::put('{rundown}', [\App\Http\Controllers\Admin\ScheduleRundownController::class, 'update'])->name('update');
            Route::delete('{rundown}', [\App\Http\Controllers\Admin\ScheduleRundownController::class, 'destroy'])->name('destroy');
            Route::post('{rundown}/reset-from-template', [\App\Http\Controllers\Admin\ScheduleRundownController::class, 'resetFromTemplate'])->name('reset-from-template');
            Route::post('{rundown}/add-item', [\App\Http\Controllers\Admin\ScheduleRundownController::class, 'addItem'])->name('add-item');
            Route::put('{rundown}/items/{item}', [\App\Http\Controllers\Admin\ScheduleRundownController::class, 'updateItem'])->name('update-item');
            Route::delete('{rundown}/items/{item}', [\App\Http\Controllers\Admin\ScheduleRundownController::class, 'deleteItem'])->name('delete-item');
            Route::post('{rundown}/items/{item}/duplicate', [\App\Http\Controllers\Admin\ScheduleRundownController::class, 'duplicateItem'])->name('duplicate-item');
            Route::post('{rundown}/reorder-items', [\App\Http\Controllers\Admin\ScheduleRundownController::class, 'reorderItems'])->name('reorder-items');
            Route::get('{rundown}/pdf-view', [\App\Http\Controllers\Admin\ScheduleRundownController::class, 'pdfView'])->name('pdf-view');
            Route::get('{rundown}/pdf-download', [\App\Http\Controllers\Admin\ScheduleRundownController::class, 'pdfDownload'])->name('pdf-download');
        });

        Route::post('upload-image', [\App\Http\Controllers\Admin\UploadController::class, 'image'])->name('upload.image');
    });

    // Frontend routes with language prefix
    Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('homepage');

    // travel packages
    Route::get('travel-packages', [\App\Http\Controllers\TravelPackageController::class, 'index'])->name('travel_package.index');
    Route::get('travel-packages/{travel_package:slug}', [\App\Http\Controllers\TravelPackageController::class, 'show'])->name('travel_package.show');
    Route::get('travel-packages/{travel_package:slug}/book', [\App\Http\Controllers\TravelPackageController::class, 'book'])->name('travel_package.book');

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

    // activities
    Route::get('activities', [\App\Http\Controllers\ActivityController::class, 'index'])->name('activities.index');
    Route::get('activities/{activity:slug}', [\App\Http\Controllers\ActivityController::class, 'show'])->name('activities.show');

    // testimonials
    Route::get('testimonials/create', [\App\Http\Controllers\TestimonialController::class, 'create'])->name('testimonials.create');
    Route::post('testimonials', [\App\Http\Controllers\TestimonialController::class, 'store'])->name('testimonials.store');

    // schedules
    Route::get('schedules', [\App\Http\Controllers\ScheduleController::class, 'index'])->name('schedule.index');
    
    // sitemap
    Route::get('sitemap.xml', [\App\Http\Controllers\HomeController::class, 'sitemap'])->name('sitemap');

    // AI Chatbot API
    Route::post('api/chat/send', [\App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('api/chat/welcome', [\App\Http\Controllers\ChatController::class, 'welcome'])->name('chat.welcome');
    Route::post('api/chat/clear', [\App\Http\Controllers\ChatController::class, 'clearSession'])->name('chat.clear');
});