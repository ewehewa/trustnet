<x-dashboard>

    <div class="card shadow-sm border-0 p-4 mb-4 livecharts-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="section-title mb-0 live-analysis-title">
                Live Market Charts
                <span class="live-indicator"></span>
            </h3>

            <span class="balance-text">
                Balance:
                <strong class="text-success">${{ number_format($user->balance, 2) }}</strong>
            </span>
        </div>

        <!-- MARKET SWITCH TABS -->
        <div class="market-tabs mb-3">
            <button class="market-tab active" data-symbol="BINANCE:BTCUSDT">Crypto</button>
            <button class="market-tab" data-symbol="OANDA:EURUSD">Forex</button>
            <button class="market-tab" data-symbol="TVC:GOLD">Gold</button>
            <button class="market-tab" data-symbol="NASDAQ:TSLA">Stocks</button>
        </div>

        <!-- LARGE CHART -->
        <div class="livechart-container rounded-3 shadow-sm">
            <div id="live_market_chart"></div>
        </div>
    </div>

    <script src="https://s3.tradingview.com/tv.js"></script>

    <script>
        let chartWidget;

        function loadLiveChart(symbol) {
            if (chartWidget) {
                document.getElementById("live_market_chart").innerHTML = "";
            }

            chartWidget = new TradingView.widget({
                autosize: true,
                symbol: symbol,
                interval: "15",
                timezone: "Etc/UTC",
                theme: "dark",
                style: "1",
                locale: "en",
                hide_side_toolbar: false,
                allow_symbol_change: false,
                container_id: "live_market_chart",
                backgroundColor: "#0b121a"
            });
        }

        document.addEventListener("DOMContentLoaded", function () {
            loadLiveChart("BINANCE:BTCUSDT");

            document.querySelectorAll(".market-tab").forEach(btn => {
                btn.addEventListener("click", () => {
                    document.querySelector(".market-tab.active").classList.remove("active");
                    btn.classList.add("active");

                    loadLiveChart(btn.dataset.symbol);
                });
            });
        });
    </script>

    <style>
        .livecharts-section {
            background: #0e1621;
            color: #dfe6ee;
            border-radius: 20px;
        }

        .livechart-container {
            height: 700px;
            background: #0b121a;
            border-radius: 18px;
            overflow: hidden;
        }

        #live_market_chart {
            width: 100%;
            height: 100%;
        }

        .market-tabs {
            display: flex;
            gap: 10px;
        }

        .market-tab {
            padding: 10px 18px;
            border-radius: 10px;
            background: #1c2733;
            color: #cbd5e1;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s ease;
        }

        .market-tab.active {
            background: #4ade80;
            color: #0e1a24;
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
