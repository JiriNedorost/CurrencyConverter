<?php

namespace App\Repository;

interface CurrenciesInterface
{
    /**
     * Gets all available currencies from DB
     * 
     * @return array<string>
     */
    public function getAllCurrencies(): array;
}
