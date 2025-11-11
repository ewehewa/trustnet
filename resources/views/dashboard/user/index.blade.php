<x-dashboard>
  <div class="container-fluid px-4">

    <!-- Welcome Section - Mobile Only -->
    <div class="row d-md-none mb-4">
      <div class="col-12">
        <h1 class="welcome-title">Welcome, {{ $user->username }}!</h1>
      </div>
    </div>

    <!-- Main Dashboard Row -->
    <div class="row">
      <!-- Left Side - Portfolio & Charts -->
      <div class="col-lg-6 col-12 mb-4">
        <!-- Portfolio -->
        <div class="portfolio-section mb-4">
          <div class="portfolio-label">MY BALANCE</div>
          <div class="portfolio-value">${{ number_format($user->balance, 2) }}</div>

          <!-- Live Stock Ticker -->
          <div class="tradingview-widget-container mb-3">
            <div class="tradingview-widget-container__widget"></div>
            <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
              {
                "symbols": [
                  { "proName": "NASDAQ:AAPL", "title": "Apple" },
                  { "proName": "NASDAQ:GOOGL", "title": "Google" },
                  { "proName": "NASDAQ:MSFT", "title": "Microsoft" },
                  { "proName": "NASDAQ:AMZN", "title": "Amazon" },
                  { "proName": "NASDAQ:TSLA", "title": "Tesla" },
                  { "proName": "NYSE:BRK.B", "title": "Berkshire" },
                  { "proName": "NASDAQ:NVDA", "title": "NVIDIA" }
                ],
                "showSymbolLogo": true,
                "colorTheme": "dark",
                "isTransparent": true,
                "displayMode": "regular",
                "locale": "en"
              }
            </script>
          </div>

          <!-- Live Crypto Chart -->
          <div class="tradingview-widget-container">
            <div class="tradingview-widget-container__widget"></div>
            <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-mini-symbol-overview.js" async>
              {
                "symbol": "BINANCE:BTCUSDT",
                "width": "100%",
                "height": "364",
                "locale": "en",
                "dateRange": "12M",
                "colorTheme": "dark",
                "isTransparent": true,
                "autosize": true
              }
            </script>
          </div>
        </div>
      </div>

      <!-- Right Side - Stats & Auto Trading -->
      <div class="col-lg-6 col-12">
        <div class="row mb-4">
          <div class="col-6 mb-3">
            <div class="stat-card">
              <div class="stat-content">
                <div class="stat-label">TOTAL DEPOSIT</div>
                <div class="stat-value">${{ number_format($user->deposits->sum('amount'), 2) }}</div>
              </div>
              <div class="stat-icon bg-cyan">
                <i class="fas fa-dollar-sign"></i>
              </div>
            </div>
          </div>
          <div class="col-6 mb-3">
            <div class="stat-card">
              <div class="stat-content">
                <div class="stat-label">TOTAL PROFIT</div>
                <div class="stat-value">${{ number_format($user->profits->sum('amount'), 2) }}</div>
              </div>
              <div class="stat-icon bg-green">
                <i class="fas fa-chart-line"></i>
              </div>
            </div>
          </div>
          <div class="col-6 mb-3">
            <div class="stat-card">
              <div class="stat-content">
                <div class="stat-label">BONUS</div>
                <div class="stat-value">${{ number_format($totalBonus, 2) }}</div>
              </div>
              <div class="stat-icon bg-orange">
                <i class="fas fa-gift"></i>
              </div>
            </div>
          </div>
          <div class="col-6 mb-3">
            <div class="stat-card">
              <div class="stat-content">
                <div class="stat-label">WITHDRAWALS</div>
                <div class="stat-value">${{ number_format($user->withdrawals->sum('amount'), 2) }}</div>
              </div>
              <div class="stat-icon bg-red">
                <i class="fas fa-arrow-down"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Auto Trading Section -->
        <div class="auto-trading-section mobile-margin-bottom mb-4">
          <h3 class="auto-trading-title">Auto Trading</h3>
          <p class="auto-trading-description">
            Earn profits by securely investing in stocks, crypto, REITs, ETFs and Bonds with our world-class auto-trading software.
          </p>
          <div class="auto-trading-content">
            {{-- <p class="no-plan-text">You do not have an active plan at the moment.</p> --}}
            <a href="{{ route('show.investment') }}" class="text-decoration-none">
                <button class="btn btn-invest">Invest in a plan</button>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Trading Section -->
<div class="row mb-4">
  <div class="col-12">
    <div class="trading-section card shadow-sm border-0 p-4">
      <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h3 class="section-title mb-0 live-trading-title">
          Live Trading
          <span class="live-indicator"></span>
        </h3>
        <span class="balance-text">
          Balance: <strong class="text-success">${{ number_format($user->balance, 2) }}</strong>
        </span>
      </div>

      <div class="row">
        <!-- Left: Trade Controls -->
        <div class="col-lg-4 mb-4">
          <form id="tradeForm">
            <div class="mb-3">
              <label class="form-label">Asset Type</label>
              <select class="form-select" id="assetType">
                <option value="crypto">Crypto</option>
                <option value="stocks">Stocks</option>
                <option value="forex">Forex</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Asset Name</label>
              <select class="form-select" id="assetName">
                <option value="BTCUSDT">BTC/USDT</option>
                <option value="ETHUSDT">ETH/USDT</option>
                <option value="AAPL">AAPL (Apple)</option>
                <option value="AMZN">AMZN (Amazon)</option>
                <option value="EURUSD">EUR/USD</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Leverage</label>

              <!-- Leverage Buttons Row -->
              <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                <!-- Prefilled Input Styled Like Button -->
                <input
                  type="text"
                  id="selectedLeverage"
                  class="form-control leverage-display"
                  value="1x"
                  readonly
                >

                <!-- Leverage Buttons -->
                @foreach([1,5,10,25,50,75,100] as $x)
                  <button
                    type="button"
                    class="btn btn-outline-light leverage-btn"
                    data-value="{{ $x }}"
                  >{{ $x }}x</button>
                @endforeach
              </div>

              <!-- Leverage Slider -->
              <input
                type="range"
                class="form-range leverage-slider"
                id="leverageRange"
                min="1"
                max="100"
                step="1"
                value="1"
              >
            </div>



            <div class="mb-3">
              <label class="form-label">Trade Duration (minutes)</label>
              <input type="number" class="form-control" id="tradeDuration" value="5" min="1">
            </div>

            <div class="mb-3">
              <label class="form-label">Amount ($)</label>
              <input type="number" class="form-control" id="tradeAmount" placeholder="Enter amount" min="1" required>
            </div>

            <div class="row mb-4">
              <div class="col-md-6 mb-3 mb-md-0">
                <label class="form-label">Take Profit (%)</label>
                <input type="number" class="form-control" id="takeProfit" step="0.1" value="1.00">
              </div>
              <div class="col-md-6">
                <label class="form-label">Stop Loss (%)</label>
                <input type="number" class="form-control" id="stopLoss" step="0.1" value="0.00">
              </div>
            </div>

            <div class="d-flex gap-3">
              <button type="button" class="btn btn-success w-50 py-2 fw-bold" id="buyBtn">
                <i class="fas fa-arrow-up me-1"></i> Buy
              </button>
              <button type="button" class="btn btn-danger w-50 py-2 fw-bold" id="sellBtn">
                <i class="fas fa-arrow-down me-1"></i> Sell
              </button>
            </div>
          </form>
        </div>

        <!-- Right: Live Chart -->
        <div class="col-lg-8">
          <div class="tradingview-widget-container rounded-3 overflow-hidden shadow-sm full-height-chart">
            <div id="tradingview_chart"></div>
            <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
            <script>
              document.addEventListener("DOMContentLoaded", function () {
                new TradingView.widget({
                  autosize: true,
                  symbol: "BINANCE:BTCUSDT",
                  interval: "30",
                  timezone: "Etc/UTC",
                  theme: "dark",
                  style: "1",
                  locale: "en",
                  enable_publishing: false,
                  backgroundColor: "#0e1621",
                  container_id: "tradingview_chart"
                });
              });

              // Leverage button toggle
              const leverageBtns = document.querySelectorAll(".leverage-btn");
              leverageBtns.forEach(btn => {
                btn.addEventListener("click", function() {
                  leverageBtns.forEach(b => b.classList.remove("active"));
                  this.classList.add("active");
                  document.getElementById("selectedLeverage").value = this.dataset.value;
                });
              });
            </script>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Market Data Charts Section -->
<div class="row mb-4">
  <!-- Cryptocurrency Chart -->
  <div class="col-lg-6 col-12 mb-4">
    <div class="mb-3">
      <h6 class="market-chart-title">Cryptocurrency Market</h6>
      <div class="tradingview-widget-container rounded-3 overflow-hidden" style="height:550px;">
        <div id="crypto_market_chart"></div>
        <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
        <script>
          new TradingView.widget({
            "height": 400,
            "width": "100%",
            "symbol": "BINANCE:BTCUSDT",
            "interval": "60",
            "timezone": "Etc/UTC",
            "theme": "dark",
            "style": "1",
            "locale": "en",
            "toolbar_bg": "transparent",
            "enable_publishing": false,
            "hide_top_toolbar": false,
            "container_id": "crypto_market_chart"
          });
        </script>
      </div>
    </div>
  </div>

  <!-- Stock Market Chart -->
  <div class="col-lg-6 col-12 mb-4">
    <div class="mb-3">
      <h6 class="market-chart-title">Stock Market</h6>
      <div class="tradingview-widget-container rounded-3 overflow-hidden" style="height:1000px;">
        <div id="stock_market_chart"></div>
        <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
        <script>
          new TradingView.widget({
            "height": 400,
            "width": "100%",
            "symbol": "NASDAQ:AAPL",
            "interval": "60",
            "timezone": "Etc/UTC",
            "theme": "dark",
            "style": "1",
            "locale": "en",
            "toolbar_bg": "transparent",
            "enable_publishing": false,
            "hide_top_toolbar": false,
            "container_id": "stock_market_chart"
          });
        </script>
      </div>
    </div>
  </div>
</div>


</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // ===== Transaction Tabs =====
    const tabButtons = document.querySelectorAll('.tab-btn');
    const depositTable = document.getElementById('depositTable');
    const withdrawalTable = document.getElementById('withdrawalTable');

    tabButtons.forEach(button => {
      button.addEventListener('click', function () {
        tabButtons.forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');

        if (this.dataset.tab === 'deposit') {
          depositTable.style.display = '';
          withdrawalTable.style.display = 'none';
        } else {
          depositTable.style.display = 'none';
          withdrawalTable.style.display = '';
        }
      });
    });

    // ===== Leverage Button + Prefill + Slider =====
    const leverageBtns = document.querySelectorAll('.leverage-btn');
    const leverageInput = document.getElementById('selectedLeverage');
    const leverageRange = document.getElementById('leverageRange');

    leverageBtns.forEach(btn => {
      btn.addEventListener('click', function () {
        leverageBtns.forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        const value = parseInt(this.dataset.value);
        leverageInput.value = value + 'x';
        if (leverageRange) {
          leverageRange.value = value;
          updateLeverageSliderColor(leverageRange);
        }
      });
    });

    // Update input and button when slider changes
    if (leverageRange) {
      leverageRange.addEventListener('input', function () {
        const val = parseInt(this.value);
        leverageInput.value = val + 'x';
        leverageBtns.forEach(b => b.classList.remove('active'));
        leverageBtns.forEach(b => {
          if (parseInt(b.dataset.value) === val) b.classList.add('active');
        });
        updateLeverageSliderColor(this);
      });

      // Initialize slider color
      updateLeverageSliderColor(leverageRange);
    }

    // Set default leverage button active
    if (leverageBtns.length > 0) {
      leverageBtns[0].classList.add('active');
      leverageInput.value = leverageBtns[0].dataset.value + 'x';
      if (leverageRange) {
        leverageRange.value = leverageBtns[0].dataset.value;
        updateLeverageSliderColor(leverageRange);
      }
    }

    // ===== Dynamic Slider Fill Color =====
    function updateLeverageSliderColor(slider) {
      const val = ((slider.value - slider.min) / (slider.max - slider.min)) * 100;
      slider.style.background = `linear-gradient(to right, #00bcd4 ${val}%, #2a3b4d ${val}%)`;
    }
  });
</script>

<style>
  /* ======== Chart Layout Adjustments ======== */
  .full-height-chart {
    height: 100%;
    min-height: 600px;
    display: flex;
    flex-direction: column;
  }

  .tradingview-widget-container {
    flex: 1;
    height: 100% !important;
    width: 100% !important;
    background: transparent !important;
  }
  

  #tradingview_chart {
    height: 100% !important;
    width: 100% !important;
  }

  /* ======== Make Chart Wider Than Controls ======== */
  @media (min-width: 992px) {
    .trading-section .row > .col-lg-5 {
      flex: 0 0 35%;
      max-width: 35%;
    }

    .trading-section .row > .col-lg-7 {
      flex: 0 0 65%;
      max-width: 65%;
    }
  }

  /* Improve responsiveness */
  @media (max-width: 991.98px) {
    .full-height-chart {
      min-height: 400px;
    }
  }

  /* ======== Trading Section Styling ======== */
  .trading-section {
    background: #0e1621;
    border-radius: 20px;
    color: #e0e0e0;
  }

  .section-title {
    font-weight: 700;
    font-size: 1.2rem;
  }

  .balance-text {
    font-size: 0.95rem;
  }

  /* ======== Trade Controls - Light Inputs & Labels ======== */
  .trading-section .form-label {
    color: #b0bec5; /* softer text */
    font-weight: 500;
  }

  .trading-section .form-control,
  .trading-section .form-select {
    background-color: #1a2533;
    border: 1px solid #2a3b4d;
    color: #f5f5f5;
    border-radius: 8px;
    padding: 10px;
    transition: border-color 0.3s ease;
  }

  .trading-section .form-control::placeholder {
    color: #9ba5b1;
  }

  .trading-section .form-control:focus,
  .trading-section .form-select:focus {
    border-color: #00bcd4;
    box-shadow: 0 0 0 0.2rem rgba(0, 188, 212, 0.2);
  }

  /* ======== Buttons & Leverage ======== */
  .leverage-btn {
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    color: #d9e6f2;
    background-color: #1a2533;
    border-color: #2a3b4d;
    transition: all 0.2s ease;
  }

  .leverage-btn.active,
  .leverage-btn:hover {
    background-color: #00bcd4;
    border-color: #00bcd4;
    color: #fff;
  }

  .btn-success,
  .btn-danger {
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
  }

  /* ======== Chart Container Height ======== */
  .tradingview-widget-container {
    height: 460px;
  }

  /* ======== Status Badges ======== */
  .status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
  }

  .status-badge.pending {
    background-color: rgba(245, 158, 11, 0.2);
    color: #f59e0b;
  }

  .status-badge.completed {
    background-color: rgba(16, 185, 129, 0.2);
    color: #10b981;
  }

  .status-badge.failed {
    background-color: rgba(239, 68, 68, 0.2);
    color: #ef4444;
  }

  @media (max-width: 767.98px) {
    .mobile-margin-bottom {
      margin-bottom: 2rem;
    }
  }

  /* ======== LIVE TRADING HEADER STYLE ======== */
  .live-trading-title {
    font-size: 1rem;
    font-weight: 600;
    color: #4ade80;
    display: flex;
    align-items: center;
    gap: 8px;
    letter-spacing: 0.5px;
  }

  .live-indicator {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: #22c55e;
    box-shadow: 0 0 10px #22c55e;
    animation: pulse 1.2s infinite ease-in-out;
  }

  @keyframes pulse {
    0% {
      transform: scale(1);
      opacity: 1;
    }
    50% {
      transform: scale(1.6);
      opacity: 0.5;
    }
    100% {
      transform: scale(1);
      opacity: 1;
    }
  }
#selectedLeverage {
  background-color: #1a2533;
  color: #00bcd4;
  font-weight: 600;
  text-align: center;
  border: 1px solid #2a3b4d;
}

/* Make leverage input look like a button */
.leverage-display {
  width: 70px;
  text-align: center;
  background-color: #1a2533;
  border: 1px solid #2a3b4d;
  color: #00bcd4;
  font-weight: 600;
  border-radius: 8px;
  cursor: default;
  transition: all 0.2s ease;
  height: 38px; /* matches Bootstrap btn height */
}

.leverage-display:focus {
  outline: none;
  box-shadow: none;
}

/* ===== Bluish Progress Slider ===== */
.leverage-slider {
  width: 100%;
  height: 6px;
  border-radius: 5px;
  appearance: none;
  background: linear-gradient(to right, #00bcd4 1%, #2a3b4d 1%);
  outline: none;
  transition: background 0.3s ease;
  cursor: pointer;
}

.leverage-slider::-webkit-slider-thumb {
  appearance: none;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  background: #00bcd4;
  box-shadow: 0 0 8px rgba(0, 188, 212, 0.6);
  cursor: pointer;
  transition: background 0.3s ease;
}

.leverage-slider::-webkit-slider-thumb:hover {
  background: #26d9f8;
}

.leverage-slider::-moz-range-thumb {
  width: 18px;
  height: 18px;
  border-radius: 50%;
  background: #00bcd4;
  border: none;
  box-shadow: 0 0 8px rgba(0, 188, 212, 0.6);
  cursor: pointer;
}

/* Smaller chart titles */
  .market-chart-title {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #b0bec5;
  }

</style>

</x-dashboard>
