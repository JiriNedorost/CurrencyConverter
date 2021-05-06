<?php

namespace App\Repository\Eloquent;

use App\Models\Conversions;
use App\Repository\ConversionsInterface;

class ConversionsRepository implements ConversionsInterface
{
    /**
     * Conversions model
     */
    private Conversions $conversions;

    public function __construct(Conversions $conversions)
    {
        $this->conversions = $conversions;
    }

    /**
     *  Returns combined name and symbol of most converted currency
     * 
     * @return string
     */
    public function getMostConverted(): string
    {
        $mostConverted = $this->conversions
            ->select('destination_currency')
            ->groupBy('destination_currency')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(1)
            ->first();
        if ($mostConverted) {
            return $mostConverted->destination_currency;
        } else {
            return "No conversions yet";
        }
        
    }

    /**
     *  Returns count of total conversions
     * 
     *  @return int
     */
    public function getTotalConversions(): int
    {
        return $this->conversions->count('id');
    }

    /**
     * Returns total converted amount in USD
     * 
     * @return float
     */
    public function getTotalConverted(): float
    {
        return round($this->conversions->sum('amount'), 2);
    }
}
