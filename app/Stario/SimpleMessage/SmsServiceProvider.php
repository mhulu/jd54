<?php

namespace Star\SimpleMessage;

use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config.php' => config_path('sms.php')
        ]);
    }
    // public function register()
    // {
    // 	$this->app->singleton(SmsApi::class, function () {
    // 		return new SmsApi();
    // 	});
    // }
}
