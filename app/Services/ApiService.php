<?php

namespace App\Services;

use App\Models\Currencies;
use Illuminate\Http\Client\Factory as HttpClient;

class ApiService
{
    /**
     *  Guzzle HttpClient
     *  @var http
     */
    private $http;

    /**
     *  Currencies database model
     *  @var currencies
     */
    private $currencies;

    public function __construct(HttpClient $http, Currencies $currencies)
    {
        $this->http = $http;
        $this->currencies = $currencies;
    }

    /**
     * This function gets all available currencies from providers API and saves them to local DB.
     * Automatically run once a day and on demand when we can't find requested currency in API
     * On successful API query it deletes all rows from DB and inserts new ones
     * 
     * @return void
     */
    public function getAllCurrencies(): void
    {
        $response = $this->http->retry(3, 1000)->get(config('services.openexchange.endpoint') . "/currencies.json");
        if ($response->successful()) {

            $this->currencies->truncate();

            $allCurrencies = $response->json();
            foreach ($allCurrencies as $shortcut => $fullName) {
                $insert[] = ['symbol' => $shortcut, 'name' => $fullName, 'combined_name' => $shortcut . ' - ' . $fullName];
            }

            $this->currencies->insert($insert);
        }
    }
}
