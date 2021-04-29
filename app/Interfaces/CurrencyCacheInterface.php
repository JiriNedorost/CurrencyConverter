<?php

namespace App\Interfaces;

interface CurrencyCacheInterface
{
    
    /**
     * Gets selected currency from cache
     * If last update is more than 1 hour ago, it refreshes rates first
     * 
     * @param string $currency
     * 
     * @return float
     */
    public function getRate(string $currency): float;

}
