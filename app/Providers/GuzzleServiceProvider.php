<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;

class GuzzleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    protected $defer = true;
    
    public function register()
    {
        $this->app->bind('GuzzleHttp\Client', function () {
            return new Client();
        });
    }

    public function provides()
    {
        return ['GuzzleHttp\Client'];
    }
}
