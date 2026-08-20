<?php

namespace App\Providers;

use App\Enums\MenuType;
use App\Models\DashboardMenu;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $menus = DashboardMenu::orderBy('display_ordering')->get();
            $view->with([
                'menus'  => $menus,
            ]);
        });
    }
}
