<?php

namespace App\Interfaces;

interface ApiInterface
{
    
    /**
     * Function to save all available currencies from API to local DB
     * 
     * @return void
     */
    public function saveAllCurrencies(): void;



    /**
     *  Returns an array of all current rates from the specified API or nothing on failed request
     * 
     *  @return array<string>
     */
    public function getAllRates(): array;

}
