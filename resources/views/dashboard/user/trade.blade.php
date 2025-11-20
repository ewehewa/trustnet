<x-dashboard>
  <!-- Trading Section -->
  <div class="trading-section card shadow-sm border-0 p-4 mb-4">
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
        <form id="dashboardTradeForm" method="POST" action="{{ route('trade.place') }}" class="w-100">
          @csrf
          <!-- Asset Type -->
          <div class="mb-3">
            <label class="form-label">Asset Type</label>
            <select class="form-select" id="assetType">
              <option value="crypto">Crypto</option>
              <option value="stocks">Stocks</option>
              <option value="forex">Forex</option>
            </select>
          </div>
          <!-- Asset Name -->
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
          <!-- Leverage -->
          <div class="mb-3">
            <label class="form-label">Leverage</label>
            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
              <input type="text" id="selectedLeverage" class="form-control leverage-display" value="1x" readonly>
              @foreach([1,5,10,25,50,75,100] as $x)
                <button type="button" class="btn btn-outline-light leverage-btn" data-value="{{ $x }}">{{ $x }}x</button>
              @endforeach
            </div>
            <input type="range" class="form-range leverage-slider" id="leverageRange" min="1" max="100" step="1" value="1">
          </div>
          <!-- Trade Duration -->
          <div class="mb-3">
            <label class="form-label">Trade Duration (minutes)</label>
            <input type="number" class="form-control" id="tradeDuration" value="5" min="1">
          </div>
          <!-- Amount -->
          <div class="mb-3">
            <label class="form-label">Amount ($)</label>
            <input type="number" class="form-control" id="tradeAmount" placeholder="Enter amount" min="1" required>
          </div>
          <!-- Take Profit & Stop Loss -->
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
          <!-- Hidden Fields -->
          <input type="hidden" name="asset_type" id="formAssetType" value="crypto">
          <input type="hidden" name="asset_name" id="formAssetName" value="BTCUSDT">
          <input type="hidden" name="trade_type" id="formTradeType" value="buy">
          <input type="hidden" name="leverage" id="formLeverage" value="1x">
          <input type="hidden" name="duration" id="formDuration" value="5">
          <input type="hidden" name="amount" id="formAmount" value="0">
          <input type="hidden" name="take_profit" id="formTakeProfit" value="1.00">
          <input type="hidden" name="stop_loss" id="formStopLoss" value="0.00">

          <div class="d-flex gap-3 mt-3">
            <button type="submit" class="btn btn-success w-50 py-2 fw-bold" id="buyBtn">
              <i class="fas fa-arrow-up me-1"></i> Buy
            </button>
            <button type="submit" class="btn btn-danger w-50 py-2 fw-bold" id="sellBtn">
              <i class="fas fa-arrow-down me-1"></i> Sell
            </button>
          </div>
        </form>
      </div>

      <!-- Right: Live Chart -->
      <div class="col-lg-8">
        <div class="tradingview-widget-container rounded-3 overflow-hidden shadow-sm full-height-chart">
          <div id="tradingview_chart"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Trade Records Section -->
  <div class="card shadow-sm border-0 p-4 mb-4" style="background:#0e1621; border-radius:20px;">
      <h4 class="text-white mb-3">Trade History</h4>

      <ul class="nav nav-tabs" id="tradeTabs">
          <li class="nav-item">
              <a class="nav-link active" data-bs-toggle="tab" href="#openTrades">Open Trades</a>
          </li>
          <li class="nav-item">
              <a class="nav-link" data-bs-toggle="tab" href="#closedTrades">Closed Trades</a>
          </li>
      </ul>

      <div class="tab-content mt-3">
          <!-- Open Trades -->
          <div class="tab-pane fade show active" id="openTrades">
              @if($openTrades->count() > 0)
                  <div class="table-responsive">
                      <table class="table table-dark table-hover align-middle">
                          <thead>
                              <tr>
                                  <th>Asset</th>
                                  <th>Type</th>
                                  <th>Amount</th>
                                  <th>Leverage</th>
                                  <th>Expires</th>
                                  <th>Status</th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach($openTrades as $trade)
                                  <tr>
                                      <td>{{ $trade->asset_name }}</td>
                                      <td>
                                          <span class="badge {{ $trade->trade_type === 'buy' ? 'bg-success' : 'bg-danger' }}">
                                              {{ strtoupper($trade->trade_type) }}
                                          </span>
                                      </td>
                                      <td>${{ number_format($trade->amount, 2) }}</td>
                                      <td>{{ $trade->leverage }}</td>
                                      <td>{{ $trade->expires_at->diffForHumans() }}</td>
                                      <td><span class="badge bg-warning">Running</span></td>
                                  </tr>
                              @endforeach
                          </tbody>
                      </table>
                  </div>
              @else
                  <p class="text-center text-secondary py-3">No open trades yet.</p>
              @endif
          </div>

          <!-- Closed Trades -->
          <div class="tab-pane fade" id="closedTrades">
              @if($closedTrades->count() > 0)
                  <div class="table-responsive">
                      <table class="table table-dark table-hover align-middle">
                          <thead>
                              <tr>
                                  <th>Asset</th>
                                  <th>Type</th>
                                  <th>Amount</th>
                                  <th>Leverage</th>
                                  <th>Result</th>
                                  <th>Closed At</th>
                              </tr>
                          </thead>
                          <tbody>
  @foreach($closedTrades as $trade)
      <tr>
          <td>{{ $trade->asset_name }}</td>
          <td>
              <span class="badge {{ $trade->trade_type === 'buy' ? 'bg-success' : 'bg-danger' }}">
                  {{ strtoupper($trade->trade_type) }}
              </span>
          </td>
          <td>${{ number_format($trade->amount, 2) }}</td>
          <td>{{ $trade->leverage }}</td>
          <td>
              @if($trade->profit_loss !== null)
                  <span class="badge {{ $trade->profit_loss >= 0 ? 'bg-success' : 'bg-danger' }}">
                      {{ $trade->profit_loss >= 0 ? '+' : '' }}${{ number_format($trade->profit_loss, 2) }}
                  </span>
              @else
                  <span class="badge bg-secondary">N/A</span>
              @endif
          </td>
          <td>{{ $trade->updated_at->format('M d, Y h:i A') }}</td>
      </tr>
  @endforeach
  </tbody>

                      </table>
                  </div>
              @else
                  <p class="text-center text-secondary py-3">No closed trades yet.</p>
              @endif
          </div>

      </div>
  </div>

  <!-- Modal: Insufficient Balance -->
  <div class="modal fade" id="balanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white border-0 rounded-3">
        <div class="modal-header border-0">
          <h5 class="modal-title text-warning">Insufficient Balance</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>You don’t have enough balance to place this trade.</p>
          <p><strong>Your Balance:</strong> <span id="modalBalanceDisplay"></span> USD</p>
          <p><strong>Required Balance:</strong> <span id="modalRequiredDisplay"></span> USD</p>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Success Toast (Top Right) -->
  <div class="position-fixed top-0 end-0 p-3" style="z-index:1055">
    <div id="tradeSuccessToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">Trade successfully placed!</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </div>

  <script src="https://s3.tradingview.com/tv.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // TradingView chart
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

      // Leverage buttons + slider
      const leverageBtns = document.querySelectorAll('.leverage-btn');
      const leverageInput = document.getElementById('selectedLeverage');
      const leverageRange = document.getElementById('leverageRange');

      leverageBtns.forEach(btn => btn.addEventListener('click', function () {
        leverageBtns.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const value = parseInt(this.dataset.value);
        leverageInput.value = value + 'x';
        leverageRange.value = value;
        updateLeverageSliderColor(leverageRange);
      }));

      leverageRange.addEventListener('input', function () {
        const val = parseInt(this.value);
        leverageInput.value = val + 'x';
        leverageBtns.forEach(b => b.classList.remove('active'));
        leverageBtns.forEach(b => { if(parseInt(b.dataset.value) === val) b.classList.add('active'); });
        updateLeverageSliderColor(this);
      });

      function updateLeverageSliderColor(slider) {
        const val = ((slider.value - slider.min) / (slider.max - slider.min)) * 100;
        slider.style.background = `linear-gradient(to right, #00bcd4 ${val}%, #2a3b4d ${val}%)`;
      }

      // Trade form submission with success toast
      const form = document.getElementById('dashboardTradeForm');
      const modal = new bootstrap.Modal(document.getElementById('balanceModal'));
      const toastEl = document.getElementById('tradeSuccessToast');
      const toast = new bootstrap.Toast(toastEl, { delay: 3000 });

      form.addEventListener('submit', function (e) {
        const amount = parseFloat(document.getElementById('tradeAmount').value);
        const balance = parseFloat(`{{ $user->balance }}`);
        if(amount > balance) {
          e.preventDefault();
          document.getElementById('modalBalanceDisplay').textContent = balance.toFixed(2);
          document.getElementById('modalRequiredDisplay').textContent = amount.toFixed(2);
          modal.show();
          return;
        }

        // Prefill hidden fields
        document.getElementById('formAssetType').value = document.getElementById('assetType').value;
        document.getElementById('formAssetName').value = document.getElementById('assetName').value;
        document.getElementById('formLeverage').value = leverageInput.value;
        document.getElementById('formDuration').value = document.getElementById('tradeDuration').value;
        document.getElementById('formAmount').value = amount;
        document.getElementById('formTakeProfit').value = document.getElementById('takeProfit').value;
        document.getElementById('formStopLoss').value = document.getElementById('stopLoss').value;
        document.getElementById('formTradeType').value = document.activeElement.id === 'buyBtn' ? 'buy' : 'sell';

        // Show success toast at top-right
        toast.show();

        // Switch to "Closed Trades" tab
        const closedTab = document.querySelector('#tradeTabs a[href="#closedTrades"]');
        if(closedTab) bootstrap.Tab.getOrCreateInstance(closedTab).show();
      });
    });
  </script>

  <style>
  /* Trading Section Styles */
  .trading-section { background: #0e1621; border-radius: 20px; color: #e0e0e0; }
  .section-title { font-weight: 700; font-size: 1.2rem; }
  .balance-text { font-size: 0.95rem; }
  .trading-section .form-label { color: #b0bec5; font-weight: 500; }
  .trading-section .form-control, .trading-section .form-select {
    background-color: #1a2533; border: 1px solid #2a3b4d; color: #f5f5f5; border-radius: 8px; padding: 10px;
  }
  .trading-section .form-control::placeholder { color: #9ba5b1; }
  .trading-section .form-control:focus, .trading-section .form-select:focus { border-color: #00bcd4; }
  .leverage-btn { border-radius: 8px; font-weight: 600; font-size: 0.9rem; color: #d9e6f2; background-color: #1a2533; border-color: #2a3b4d; transition: all 0.2s ease; }
  .leverage-btn.active, .leverage-btn:hover { background-color: #00bcd4; border-color: #00bcd4; color: #fff; }
  .btn-success, .btn-danger { border-radius: 8px; font-size: 1rem; font-weight: 600; }
  .full-height-chart { height: 100%; min-height: 600px; display: flex; flex-direction: column; }
  #tradingview_chart { height: 100%; width: 100%; }
  .live-trading-title { font-size: 1rem; font-weight: 600; color: #4ade80; display: flex; align-items: center; gap: 8px; letter-spacing: 0.5px; }
  .live-indicator { display: inline-block; width: 10px; height: 10px; border-radius: 50%; background-color: #22c55e; box-shadow: 0 0 10px #22c55e; animation: pulse 1.2s infinite ease-in-out; }
  @keyframes pulse { 0% { transform: scale(1); opacity: 1; } 50% { transform: scale(1.6); opacity: 0.5; } 100% { transform: scale(1); opacity: 1; } }
  #selectedLeverage { background-color: #1a2533; color: #00bcd4; font-weight: 600; text-align: center; border: 1px solid #2a3b4d; }
  .leverage-display { width: 70px; text-align: center; background-color: #1a2533; border: 1px solid #2a3b4d; color: #00bcd4; font-weight: 600; border-radius: 8px; cursor: default; height: 38px; }
  .leverage-slider { width: 100%; height: 6px; border-radius: 5px; appearance: none; background: linear-gradient(to right, #00bcd4 1%, #2a3b4d 1%); outline: none; cursor: pointer; }
  .leverage-slider::-webkit-slider-thumb, .leverage-slider::-moz-range-thumb { width: 18px; height: 18px; border-radius: 50%; background: #00bcd4; cursor: pointer; box-shadow: 0 0 8px rgba(0,188,212,0.6); }
  </style>
</x-dashboard>
