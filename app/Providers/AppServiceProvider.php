<?php

namespace App\Providers;

use App\Models\HeroSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useTailwind();

        // Share hero settings with all frontend views
        View::composer([
            'homepage',
            'about-us',
            'contact',
            'gallery',
            'homestay',
            'impact',
            'blogs.index',
            'travel_packages.index',
            'activities.index',
        ], function ($view) {
            // Determine which page hero to load based on the view name
            $viewName = $view->getName();
            $pageMap = [
                'homepage' => 'home',
                'about-us' => 'about',
                'contact' => 'contact',
                'gallery' => 'gallery',
                'homestay' => 'homestay',
                'impact' => 'impact',
                'blogs.index' => 'blog',
                'travel_packages.index' => 'packages',
                'activities.index' => 'activities',
            ];

            $page = $pageMap[$viewName] ?? 'home';
            $heroSetting = HeroSetting::getForPage($page);

            $view->with('heroSetting', $heroSetting);
        });
    }
}
