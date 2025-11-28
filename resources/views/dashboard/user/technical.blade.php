<x-dashboard>

    <div class="card shadow-sm border-0 p-4 mb-4 technical-section">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="section-title mb-0 live-analysis-title">
                Technical Analysis
                <span class="live-indicator"></span>
            </h3>

            <span class="balance-text">
                Balance: 
                <strong class="text-success">${{ number_format($user->balance, 2) }}</strong>
            </span>
        </div>

        <div class="technical-chart-container rounded-3 shadow-sm">
            <div id="technical_analysis_chart"></div>
        </div>
    </div>

    <script src="https://s3.tradingview.com/tv.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new TradingView.widget({
                autosize: true,
                symbol: "BINANCE:BTCUSDT",
                interval: "30",
                timezone: "Etc/UTC",
                theme: "dark",
                style: "1",
                locale: "en",
                enable_publishing: false,
                hide_side_toolbar: false,
                allow_symbol_change: true,
                studies: [
                    "MASimple@tv-basicstudies",
                    "MAExp@tv-basicstudies",
                    "RSI@tv-basicstudies",
                    "MACD@tv-basicstudies"
                ],
                container_id: "technical_analysis_chart",
                backgroundColor: "#0e1621"
            });
        });
    </script>

    <style>
        .technical-section {
            background: #0e1621;
            border-radius: 20px;
            color: #e0e0e0;
        }

        .technical-chart-container {
            height: 700px;
            background: #0b121a;
            overflow: hidden;
            border-radius: 16px;
        }

        #technical_analysis_chart {
            height: 100%;
            width: 100%;
        }

        .section-title {
            font-weight: 700;
            font-size: 1.25rem;
        }

        .live-analysis-title {
            font-weight: 600;
            font-size: 1.1rem;
            color: #4ade80;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .live-indicator {
            width: 10px;
            height: 10px;
            background-color: #22c55e;
            border-radius: 50%;
            box-shadow: 0 0 10px #22c55e;
            animation: pulse 1.3s infinite ease-in-out;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.6); opacity: 0.5; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>

</x-dashboard>
