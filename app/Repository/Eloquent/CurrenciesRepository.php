<?php

namespace App\Repository\Eloquent;

use App\Models\Currencies;
use App\Repository\CurrenciesInterface;

class CurrenciesRepository implements CurrenciesInterface
{
    /**
     * Currencies model
     */
    private Currencies $currencies;

    public function __construct(Currencies $currencies)
    {
        $this->currencies = $currencies;
    }

    /**
     * Gets all available currencies from DB
     * 
     * @return array<string>
     */
    public function getAllCurrencies(): array
    {
        $allCurrencies = $this->currencies
            ->pluck('combined_name')
            ->all();

        return $allCurrencies;
    }
}
