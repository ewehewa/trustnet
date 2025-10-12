<x-dashboard>
  <div class="container-fluid px-4">
    <h3 class="page-title">Transaction Records</h3>

    <!-- Transaction Tabs -->
    <div class="content-card">
      <div class="tab-container">
        <button class="tab-btn active" data-tab="deposit">Deposit</button>
        <button class="tab-btn" data-tab="withdrawal">Withdrawal</button>
      </div>

      <!-- Deposit Table -->
      <div id="depositTable" class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Amount</th>
              <th>Payment Mode</th>
              <th>Status</th>
              <th>Date Created</th>
            </tr>
          </thead>
          <tbody>
            @forelse (Auth::user()->deposits as $deposit)
              <tr>
                <td>${{ number_format($deposit->amount, 2) }}</td>
                <td>{{ strtoupper($deposit->payment_mode) }}</td>
                <td>
                  <span class="status-badge {{ $deposit->status ?? 'pending' }}">
                    {{ ucfirst($deposit->status ?? 'pending') }}
                  </span>
                </td>
                <td>{{ $deposit->created_at->format('Y-m-d H:i:s') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4">No deposit records found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Withdrawal Table -->
      <div id="withdrawalTable" class="table-responsive" style="display: none;">
        <table class="data-table">
          <thead>
            <tr>
              <th>Amount Requested</th>
              <th>Receiving Mode</th>
              <th>Wallet Address</th>
              <th>Status</th>
              <th>Date Created</th>
            </tr>
          </thead>
          <tbody>
            @forelse (Auth::user()->withdrawals as $withdrawal)
              <tr>
                <td>${{ number_format($withdrawal->amount, 2) }}</td>
                <td>{{ strtoupper($withdrawal->receiving_mode) }}</td>
                <td>{{ $withdrawal->address }}</td>
                <td>
                  <span class="status-badge {{ $withdrawal->status ?? 'pending' }}">
                    {{ ucfirst($withdrawal->status ?? 'pending') }}
                  </span>
                </td>
                <td>{{ $withdrawal->created_at->format('Y-m-d H:i:s') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5">No withdrawal records found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Script to manage tabs and read ?tab= from URL -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const tabButtons = document.querySelectorAll('.tab-btn');
      const depositTable = document.getElementById('depositTable');
      const withdrawalTable = document.getElementById('withdrawalTable');

      function switchTab(tab) {
        tabButtons.forEach(btn => {
          btn.classList.remove('active');
          if (btn.dataset.tab === tab) {
            btn.classList.add('active');
          }
        });

        if (tab === 'withdrawal') {
          depositTable.style.display = 'none';
          withdrawalTable.style.display = 'block';
        } else {
          depositTable.style.display = 'block';
          withdrawalTable.style.display = 'none';
        }
      }

      const urlParams = new URLSearchParams(window.location.search);
      const initialTab = urlParams.get('tab') || 'deposit';
      switchTab(initialTab);

      tabButtons.forEach(button => {
        button.addEventListener('click', function () {
          switchTab(this.dataset.tab);
        });
      });
    });
  </script>

  <!-- Styles -->
  <style>
    .status-badge {
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
    }

    .status-badge.completed {
      background: rgba(16, 185, 129, 0.2);
      color: #10b981;
    }

    .status-badge.pending {
      background: rgba(245, 158, 11, 0.2);
      color: #f59e0b;
    }

    .status-badge.failed {
      background: rgba(239, 68, 68, 0.2);
      color: #ef4444;
    }

    .table-responsive {
      overflow-x: auto;
    }
  </style>
</x-dashboard>
