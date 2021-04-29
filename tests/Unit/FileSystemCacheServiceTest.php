<?php

namespace Tests\Unit;

use App\Services\FileSystemCurrencyCacheService;
use Mockery;
use Tests\TestCase;
use Illuminate\Cache\Repository as CacheRepository;

class FileSystemCacheServiceTest extends TestCase
{
    private CacheRepository $cache;

    public function setUp(): void
    {
        parent::setUp();

        $this->cache = $this->app->make('Illuminate\Cache\Repository');
    }


    public function test_rates_are_retrieved_from_cache()
    {
        $mockedApiService = Mockery::mock('App\Interfaces\CurrencyApiInterface');
        $mockedApiService->shouldReceive('getAllRates')
            ->once()
            ->andReturn($this->mockResponse());

        (new FileSystemCurrencyCacheService($mockedApiService, $this->cache))->getRate('CZK');
        
        $eur = $this->cache->get('EUR');
        $czk = $this->cache->get('CZK');

        $this->assertEquals(0.8631, $eur);
    }

    public function test_cache_is_being_updated()
    {
        $this->cache->forever('last_update', time()-60*60*24);//set a day ago, so cache refresh is forced
        $oldValue = 20.0001;
        $this->cache->forever('CZK', $oldValue);

        $mockedApiService = Mockery::mock('App\Interfaces\CurrencyApiInterface');
        $mockedApiService->shouldReceive('getAllRates')
            ->once()
            ->andReturn($this->mockResponse());

        (new FileSystemCurrencyCacheService($mockedApiService, $this->cache))->getRate('CZK');
        
        $czk = $this->cache->get('CZK');

        $this->assertNotEquals($oldValue, $czk);
    }

    private function mockResponse()
    {
        return [
            'EUR' => 0.8631,
            'CZK' => 22.514,
            'NOK' => 10.139,
            'GBP' => 0.86008,
        ];
    }
}
