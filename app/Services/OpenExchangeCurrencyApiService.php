<?php

namespace App\Services;

use App\Interfaces\CurrencyApiInterface;
use App\Models\Currencies;
use Illuminate\Config\Repository as Config;
use Illuminate\Http\Client\Factory as HttpClient;

class OpenExchangeCurrencyApiService implements CurrencyApiInterface
{
    /**
     * Guzzle HttpClient
     */
    private HttpClient $http;

    /**
     * Currencies database model
     */
    private Currencies $currencies;

    /**
     *  Config retriever
     */
    private Config $config;

    public function __construct(HttpClient $http, Currencies $currencies, Config $config)
    {
        $this->http = $http;
        $this->currencies = $currencies;
        $this->config = $config;
    }

    /**
     * This function gets all available currencies from providers API and saves them to local DB.
     * Automatically run once a day and on demand when we can't find requested currency in API
     * On successful API query it deletes all rows from DB and inserts new ones
     * 
     * @return void
     */
    public function saveAllCurrencies(): void
    {
        $response = $this->http->retry(3, 1000)
            ->get($this->config->get('services.openexchange.endpoint') . "/currencies.json");
        if ($response->successful()) {

            $insert = [];
            $usd = false;
            $eur = false;
            $allCurrencies = $response->json();
            foreach ($allCurrencies as $shortcut => $fullName) {
                $insert[] = ['symbol' => $shortcut, 'name' => $fullName, 'combined_name' => $shortcut . ' - ' . $fullName];
                $shortcut === 'USD' ? $usd = true : false;
                $shortcut === 'EUR' ? $eur = true : false;
            }
            if (count($insert) && $usd && $eur) { //check if array has any rows and some major currencies are present
                $this->currencies->truncate();
                $this->currencies->insert($insert);
            }
        }
    }

    /**
     *  Returns an array of all current rates from the specified API or nothing on failed request
     * 
     *  @return array<string>
     */
    public function getAllRates(): array
    {
        $response = $this->http->retry(3, 1000)
            ->get($this->config->get('services.openexchange.endpoint') . '/latest.json?app_id=' . $this->config->get('services.openexchange.secret'));
        if ($response->successful()) {
            return $response->json()['rates'];
        }

        return array();
    }
}
