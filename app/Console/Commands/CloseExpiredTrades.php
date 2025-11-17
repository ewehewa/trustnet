<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Trade;
use App\Services\TradeService;

class CloseExpiredTrades extends Command
{
    protected $signature = 'trades:close-expired';
    protected $description = 'Automatically closes expired trades and records P/L';

    public function handle()
    {
        $openTrades = Trade::where('status', 'open')
            ->where('expires_at', '<=', now())
            ->get();

        if ($openTrades->isEmpty()) {
            $this->info('No trades to close at this time.');
            return;
        }

        foreach ($openTrades as $trade) {
            $finalPrice = getCurrentPrice($trade->asset_name);
            $profitLoss = calculateProfitLoss($trade, $finalPrice);

            TradeService::closeTrade($trade, $profitLoss);

            $this->info("Trade #{$trade->id} ({$trade->asset_name}) closed with P/L: $" . number_format($profitLoss, 2));
        }
    }
}
