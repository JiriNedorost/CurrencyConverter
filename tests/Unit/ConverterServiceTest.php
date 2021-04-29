<?php

namespace Tests\Unit;

use App\Models\Conversions;
use App\Models\Currencies;
use App\Interfaces\CurrencyApiInterface;
use App\Services\ConverterService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Mockery;
use Tests\TestCase;

class ConverterServiceTest extends TestCase
{

    private Currencies $currencies;
    private Conversions $conversions;
    private CurrencyApiInterface $api;

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed', ['--class' => 'CurrenciesSeeder']);

        $this->currencies = $this->app->make('App\Models\Currencies');
        $this->conversions = $this->app->make('App\Models\Conversions');
        $this->api = $this->app->make('App\Interfaces\CurrencyApiInterface');
    }

    public function test_currencies_conversion_returns_the_same_amount_if_currencies_are_equal()
    {
        $from = "EUR - Euro";
        $to = "EUR - Euro";
        $amount = 10;

        $mockedCacheService = Mockery::mock('App\Interfaces\CurrencyCacheInterface');
        $mockedCacheService->shouldReceive('getRate')
            ->times(2)
            ->andReturn(0.836564, 0.836564);

        $converted = (new ConverterService($mockedCacheService, $this->currencies, $this->conversions, $this->api))->convertCurrency($from, $to, $amount);

        $this->assertEquals(10, $converted);
    }

    public function test_currencies_conversion_returns_correct_value()
    {
        $from = "EUR - Euro";
        $to = "CZK - Czech Republic Koruna";
        $amount = 10;

        $mockedCacheService = Mockery::mock('App\Interfaces\CurrencyCacheInterface');
        $mockedCacheService->shouldReceive('getRate')
            ->times(2)
            ->andReturn(0.836564, 23.56987);

        $converted = (new ConverterService($mockedCacheService, $this->currencies, $this->conversions, $this->api))->convertCurrency($from, $to, $amount);

        $this->assertEquals(281.7461664618607, $converted);
    }
}
