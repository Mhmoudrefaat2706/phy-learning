<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use App\Models\Message;
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

    public function boot()
    {
        View::composer('admin.incloudes.header', function ($view) {
            $setting = Setting::first();

            $unreadMessages =Message::where('status', 'unread')->get();
            $unreadCount = $unreadMessages->count();
           $view->with(compact('setting', 'unreadMessages', 'unreadCount'));
        });
        View::composer('layouts.app', function ($view) {
            $setting = Setting::where('lang', app()->getLocale())->first();
           $view->with(compact('setting'));
        });
    }
}
