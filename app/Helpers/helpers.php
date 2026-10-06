<?php

if (! function_exists('format_rupiah')) {
    /**
     * Format angka menjadi format mata uang Rupiah.
     * Contoh: 12500000 → "Rp 12.500.000"
     */
    function format_rupiah(int|float $amount): string
    {
        return 'Rp '.number_format($amount, 0, ',', '.');
    }
}
