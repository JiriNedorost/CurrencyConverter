<?php

namespace App\Providers;

use App\Interfaces\ApiInterface;
use App\Services\ApiService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public $bindings = [
        ApiInterface::class => ApiService::class,
    ];

    
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
        //
    }
}
