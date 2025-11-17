<?php

namespace App\Services;

use App\Models\Trade;

class TradeService
{
    public static function closeTrade(Trade $trade, float $profitLoss)
    {
        if ($trade->status !== 'open') return;

        $trade->status = 'closed';
        $trade->profit_loss = round($profitLoss, 2);
        $trade->save();
    }
}
