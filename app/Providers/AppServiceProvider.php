<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Notification::extend('custom_push', function ($app) {
            return new class {
                public function send($notifiable, $notification)
                {
                    if (method_exists($notification, 'toCustomPush')) {
                        $notification->toCustomPush($notifiable);
                    }
                }
            };
        });
    }
}
