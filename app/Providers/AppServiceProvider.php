<?php

namespace App\Providers;

use App\Models\HeroSetting;
use App\Services\Chat\AIProviderInterface;
use App\Services\Chat\GroqProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind AIProviderInterface to GroqProvider (swap provider here)
        $this->app->bind(AIProviderInterface::class, GroqProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
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