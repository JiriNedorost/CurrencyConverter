<?php

namespace App\Providers;

use App\Interfaces\CurrencyApiInterface;
use App\Interfaces\CurrencyCacheInterface;
use App\Repository\ConversionsInterface;
use App\Repository\CurrenciesInterface;
use App\Repository\Eloquent\ConversionsRepository;
use App\Repository\Eloquent\CurrenciesRepository;
use App\Services\FileSystemCurrencyCacheService;
use App\Services\OpenExchangeCurrencyApiService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public $bindings = [
        CurrencyApiInterface::class => OpenExchangeCurrencyApiService::class,
        CurrencyCacheInterface::class => FileSystemCurrencyCacheService::class,
        ConversionsInterface::class => ConversionsRepository::class,
        CurrenciesInterface::class => CurrenciesRepository::class,
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
