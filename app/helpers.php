<?php

if (!function_exists('formatPrice')) {
    /**
     * Format price with locale-aware currency symbol.
     * Shows "Rp" for Indonesian locale (id) and "IDR" for English locale (en).
     *
     * @param  int|float|null  $price
     * @param  bool  $withSpace  Whether to include space between symbol and number
     * @return string
     */
    function formatPrice($price, bool $withSpace = true): string
    {
        if ($price === null) {
            $price = 0;
        }

        $locale = app()->getLocale();
        $currency = $locale === 'en' ? 'IDR' : 'Rp';
        $space = $withSpace ? ' ' : '';
        $formatted = number_format((float) $price, 0, ',', '.');

        return $currency . $space . $formatted;
    }
}
