<x-dashboard>

    <div class="card shadow-sm border-0 p-4 mb-4 market-calendar-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="section-title mb-0 live-analysis-title">
                Market Calendar
                <span class="live-indicator"></span>
            </h3>

            <span class="balance-text">
                Balance:
                <strong class="text-success">${{ number_format($user->balance, 2) }}</strong>
            </span>
        </div>

        <!-- CALENDAR WIDGET -->
        <div class="calendar-container shadow-sm">
            <div id="economic_calendar_widget"></div>
        </div>
    </div>

    <!-- TradingView Script -->
    <script src="https://s3.tradingview.com/external-embedding/embed-widget-events.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            new TradingView.widget({
                "colorTheme": "dark",
                "isTransparent": false,
                "width": "100%",
                "height": "700",
                "locale": "en",
                "importanceFilter": "-1,0,1",
                "currencyFilter": "USD,EUR,GBP,CAD,AUD,JPY,CHF,NZD",
                "container_id": "economic_calendar_widget",
                "backgroundColor": "#0b121a"
            });
        });
    </script>

    <style>
        .market-calendar-section {
            background: #0e1621;
            color: #dfe6ee;
            border-radius: 20px;
        }

        .calendar-container {
            background: #0b121a;
            border-radius: 18px;
            padding: 10px;
            overflow: hidden;
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
