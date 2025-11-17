<?php

if (!function_exists('getCurrentPrice')) {
    function getCurrentPrice(string $assetName): float
    {
        // Dummy simulation: random price around a base
        $basePrices = [
            'BTCUSDT' => 100,
            'ETHUSDT' => 50,
            'AAPL'   => 150,
            'AMZN'   => 120,
            'EURUSD' => 1.10,
        ];

        $base = $basePrices[$assetName] ?? 100;
        // Simulate ±10% movement
        $variation = rand(-1000, 1000) / 10000 * $base;
        return round($base + $variation, 2);
    }
}

if (!function_exists('calculateProfitLoss')) {
    function calculateProfitLoss($trade, float $finalPrice): float
    {
        // Dummy calculation: simplified P/L
        $entryPrice = 100; // You can store actual entry price in DB later
        $multiplier = str_replace('x', '', $trade->leverage);
        $amount = $trade->amount;

        if ($trade->trade_type === 'buy') {
            return ($finalPrice - $entryPrice) * $amount * $multiplier / 100;
        } else {
            return ($entryPrice - $finalPrice) * $amount * $multiplier / 100;
        }
    }
}
