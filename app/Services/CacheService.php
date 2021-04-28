<?php

namespace App\Services;

use App\Services\ApiService;
use Illuminate\Cache\Repository as CacheRepository;

class CacheService
{
    /**
     * ApiService object
     * @var api
     */
    private $api;

    /**
     * Instance of cache repository
     * @var cache
     */
    private $cache;

    public function __construct(ApiService $api, CacheRepository $cache)
    {
        $this->api = $api;
        $this->cache = $cache;
    }

    /**
     * Gets all current rates from API and saves them to local cache, along with current timestamp
     * 
     * @return void
     */
    public function saveAllRatesToCache(): void
    {
        $rates = $this->api->getAllRates();
        if (is_array($rates)) {
            $this->cache->forever('last_update', time());
            foreach($rates as $currency => $rate) {
                $this->cache->forever($currency, $rate);
            }
        }
    }
}
