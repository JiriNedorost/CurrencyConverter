<?php

namespace App\Services;

use App\Models\Currencies;
use App\Services\CacheService;

class ConverterService
{
    /**
     * CacheService object
     * @var cache
     */
    private $cache;

    /**
     * Currencies DB model
     * @var currencies
     */
    private $currencies;

    public function __construct(CacheService $cache, Currencies $currencies)
    {
        $this->cache = $cache;
        $this->currencies = $currencies;
    }

    /**
     * Converts Specified amount from one currency to another
     * All currencies are coverted using USD as base
     * 
     * @param string from Requires specific format as saved in table Currencies - combined_name column
     * @param string to Requires specific format as saved in table Currencies - combined_name column
     * @param float amount
     * 
     * @return float
     */
    public function convertCurrency(string $from, string $to, float $amount): float
    {
        //TODO checking if currency exist, if not refresh all

        $fromSymbol = $this->currencies->where('combined_name', $from)->first()->symbol;
        $toSymbol = $this->currencies->where('combined_name', $to)->first()->symbol;

        $rateFrom = $this->cache->getRate($fromSymbol);
        $rateTo = $this->cache->getRate($toSymbol);

        return ($amount/$rateFrom)*$rateTo;
    }
}
