<?php

namespace App\Services;

use App\Models\Currency;

class CurrencyService
{
    /**
     * Resolve or validate exchange rate for the currency (e.g. external API).
     * Returns false if the currency code cannot be used (caller may roll back create/update).
     */
    public function setCurrencyExchangeRate(Currency $currency): bool
    {
        // Extend with real API / rate logic when available.
        return true;

    }// end of setCurrencyExchangeRate

}// end of class
