<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use App\Models\Notification;
use Illuminate\Pagination\Paginator;


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
        View::composer('*', function ($view) {

            $view->with(
                'unreadCount',
                Notification::where(
                    'is_read',
                    false
                )->count()
            );
        });

        View::composer('*', function ($view) {

            $view->with(
                'unreadCount',
                Notification::where(
                    'is_read',
                    false
                )->count()
            );
        });

        Paginator::useBootstrapFive();

        Carbon::setLocale('id');
    }
}
