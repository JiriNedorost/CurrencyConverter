<?php

namespace App\Services;

use App\Interfaces\CurrencyApiInterface;
use App\Interfaces\CurrencyCacheInterface;
use Illuminate\Cache\Repository as CacheRepository;

class FileSystemCurrencyCacheService implements CurrencyCacheInterface
{
    /**
     * ApiService object
     */
    private CurrencyApiInterface $api;

    /**
     * Instance of cache repository
     */
    private CacheRepository $cache;

    public function __construct(CurrencyApiInterface $api, CacheRepository $cache)
    {
        $this->api = $api;
        $this->cache = $cache;
    }

    /**
     * Gets all current rates from API and saves them to local cache, along with current timestamp
     * 
     * @return void
     */
    private function saveAllRatesToCache(): void
    {
        $rates = $this->api->getAllRates();
        if (is_array($rates)) {
            $this->cache->forever('last_update', time());
            foreach ($rates as $currency => $rate) {

                $this->cache->forever((string)$currency, $rate);
            }
        }
    }

    /**
     * Gets selected currency from cache
     * If last update is more than 1 hour ago, it refreshes rates first
     * 
     * @param string $currency
     * 
     * @return float
     */
    public function getRate(string $currency): float
    {
        $lastUpdate = $this->cache->get('last_update');

        if ($lastUpdate < time()-60*60 ) {
            $this->saveAllRatesToCache();
        }

        $rate = $this->cache->get($currency);
        return (float)$rate;
    }
}
