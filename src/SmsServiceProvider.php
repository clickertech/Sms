<?php

namespace Clickertech\Sms;

use Illuminate\Support\ServiceProvider;
use Clickertech\Sms\Commands\SmsPublishCommand;

class SmsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/sms.php' => config_path('sms.php'),
            ], 'sms-config');

            $this->commands([
                SmsPublishCommand::class,
            ]);
        }

        $this->app->bind('clickertech-sms', function () {
            return new Sms(config('sms'));
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/sms.php', 'sms');
    }
}
