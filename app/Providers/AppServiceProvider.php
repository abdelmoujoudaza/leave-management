<?php

namespace App\Providers;

use App\Models\Leave;
use App\Observers\LeaveObserver;
use Illuminate\View\View;
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
        view()->composer('*', function(View $view) {
            $pendingCount = Leave::Pending()->count();
            $view->with('pendingCount', $pendingCount);
        });

        Leave::observe(LeaveObserver::class);
    }
}
