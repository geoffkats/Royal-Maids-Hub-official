<?php

namespace App\Helpers;

class CurrencyHelper
{
    public const CURRENCY_SYMBOL = 'UGX';
    public const CURRENCY_CODE = 'UGX';
    public const DECIMAL_PLACES = 0; // UGX typically doesn't use decimal places
    
    /**
     * Format a number as currency
     */
    public static function format($amount, $decimals = null, $symbol = null): string
    {
        $decimals = $decimals ?? self::DECIMAL_PLACES;
        $symbol = $symbol ?? self::CURRENCY_SYMBOL;
        
        return $symbol . ' ' . number_format($amount, $decimals);
    }
    
    /**
     * Format a number as currency with thousands separator
     */
    public static function formatWithSeparator($amount, $decimals = null, $symbol = null): string
    {
        $decimals = $decimals ?? self::DECIMAL_PLACES;
        $symbol = $symbol ?? self::CURRENCY_SYMBOL;
        
        return $symbol . ' ' . number_format($amount, $decimals);
    }
    
    /**
     * Get currency symbol
     */
    public static function symbol(): string
    {
        return self::CURRENCY_SYMBOL;
    }
    
    /**
     * Get currency code
     */
    public static function code(): string
    {
        return self::CURRENCY_CODE;
    }
}
