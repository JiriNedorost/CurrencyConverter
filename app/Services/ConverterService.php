<?php

namespace App\Services;

use App\Models\Conversions;
use App\Models\Currencies;
use App\Services\ApiService;
use App\Services\CacheService;
use Exception;

class ConverterService
{
    /**
     * CacheService object
     */
    private CacheService $cache;

    /**
     * Currencies DB model
     */
    private Currencies $currencies;

    /**
     * Conversions DB model
     */
    private Conversions $conversions;

    /**
     * ApiService object
     */
    private ApiService $api;

    public function __construct(CacheService $cache, Currencies $currencies, Conversions $conversions, ApiService $api)
    {
        $this->cache = $cache;
        $this->currencies = $currencies;
        $this->conversions = $conversions;
        $this->api = $api;
    }

    /**
     * Converts Specified amount from one currency to another
     * All currencies are coverted using USD as base
     * 
     * @param string $from Requires specific format as saved in table Currencies - combined_name column
     * @param string $to Requires specific format as saved in table Currencies - combined_name column
     * @param float $amount
     * 
     * @return float
     * @throws Exception when supplied with invalid to or from field
     */
    public function convertCurrency(string $from, string $to, float $amount): float
    {
        $fromSymbol = $this->currencies->where('combined_name', $from)->first()->symbol;
        $toSymbol = $this->currencies->where('combined_name', $to)->first()->symbol;

        $rateFrom = (float)$this->cache->getRate($fromSymbol);
        $rateTo = (float)$this->cache->getRate($toSymbol);

        if ($rateFrom === 0.0 or $rateTo === 0.0) { //If either is 0, we don't have them in currencies cache, maybe they were removed from API by provider? Reload currencies in DB
            $this->api->saveAllCurrencies();
            return 0.0;
        }

        //Save stats of this conversion to DB
        $this->conversions->original_currency = $from;
        $this->conversions->destination_currency = $to;
        $this->conversions->amount = $amount / $rateFrom; //Amount is in USD
        $this->conversions->save();

        return ($amount / $rateFrom) * $rateTo;
    }
}
