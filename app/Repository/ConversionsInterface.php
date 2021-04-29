<?php

namespace App\Repository;

interface ConversionsInterface
{
    /**
     *  Returns combined name and symbol of most converted currency
     * 
     * @return string
     */
    public function getMostConverted(): string;

    /**
     *  Returns count of total conversions
     * 
     *  @return int
     */
    public function getTotalConversions(): int;

    /**
     * Returns total converted amount in USD
     * 
     * @return float
     */
    public function getTotalConverted(): float;
}
