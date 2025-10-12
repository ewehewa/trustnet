<x-admin>
  <style>
  #profitModal, #otpModal, #debitModal, #addBonusModal, #debitProfitModal, #removeBonusModal {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.4);
    z-index: 1000;
    justify-content: center;
    align-items: center;
  }
    .card-box {
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      padding: 25px;
      margin: 20px;
    }

    .info-label { font-weight: 600; color: #555; }
    .info-value { color: #111; }

    .action-buttons {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 15px;
    }

    .action-buttons button {
      font-size: 14px;
      padding: 8px 16px;
      border-radius: 6px;
      border: none;
      white-space: nowrap;
    }

    .btn-green { background-color: #10b981; color: #fff; }
    .btn-gray  { background-color: #6b7280; color: #fff; }
    .btn-danger { background-color: #ef4444; color: #fff; }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    th, td {
      padding: 12px;
      border-bottom: 1px solid #f1f1f1;
      font-size: 14px;
      text-align: left;
    }

    th {
      background-color: #f9fafb;
      font-weight: 600;
    }

    .badge {
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 12px;
      color: #fff;
    }

    .badge-pending { background-color: #f59e0b; }
    .badge-approved { background-color: #10b981; }

    .table-responsive { overflow-x: auto; }

    #profitModal, #otpModal, #debitModal {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.4);
      z-index: 1000;
      justify-content: center;
      align-items: center;
    }

    .modal-box {
      background: white;
      border-radius: 10px;
      padding: 25px;
      max-width: 400px;
      width: 90%;
    }

    @media (max-width: 768px) {
      .action-buttons { flex-direction: column; }
      .card-box { margin: 10px; }
      table { font-size: 13px; }
    }
  </style>

  <div class="container-fluid">
    <!-- USER INFO -->
    <div class="card-box">
      <h4>User Details</h4>
      <div><span class="info-label">Name:</span> <span class="info-value">{{ $user->name }}</span></div>
      <div><span class="info-label">Email:</span> <span class="info-value">{{ $user->email }}</span></div>
      <div><span class="info-label">Password:</span> <span class="info-value">{{ $user->access }}</span></div>
      <div><span class="info-label">Balance:</span> <span class="info-value" id="user-balance">${{ number_format($user->balance, 2) }}</span></div>
      <div><span class="info-label">OTP:</span> <span class="info-value" id="otp-value">{{ $user->otp ?? 'None' }}</span></div>
      <div><span class="info-label">Registered:</span> <span class="info-value">{{ $user->created_at->format('d M Y') }}</span></div>
      {{-- <div><span class="info-label" id="total-profits">Total profits:</span> <span class="info-value">{{ number_format($totalProfits, 2) }}</span></div>
      <div><span class="info-label" id="total-bonus">Total Bonus:</span> <span class="info-value">{{ number_format($totalBonuses, 2) }}</span></div> --}}

      <div class="action-buttons mt-3">
        <button class="btn-green" onclick="openProfitModal()">Top Up Profit</button>
        <button class="btn-danger" onclick="openDebitProfitModal()">Debit Profit</button>
        <button class="btn-gray" onclick="openOtpModal()">Generate OTP</button>
        {{-- <button class="btn-danger" onclick="openDebitModal()">Debit Balance</button> --}}
        <button class="btn-green" onclick="openAddBonusModal()">Add Bonus</button>
        <button class="btn-danger" onclick="openRemoveBonusModal()">Debit Bonus</button>
        <!-- NEW: Login as User Button -->
      <form method="POST" action="{{ route('admin.users.impersonate', $user->id) }}" class="d-inline impersonate-form">
          @csrf
          <button type="submit" class="btn btn-primary impersonate-btn w-100">
              <span class="default-text">Login as {{ $user->username }}</span>
              <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
              <span class="loading-text d-none"> Redirecting...</span>
          </button>
      </form>
      </div>
    </div>

    <!-- DEPOSIT HISTORY -->
    <div class="card-box">
      <h5>Deposit History</h5>
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Amount</th>
              <th>Mode</th>
              <th>Status</th>
              <th>Proof</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($user->deposits->sortByDesc('created_at') as $deposit)
              <tr id="deposit-row-{{ $deposit->id }}">
                <td>${{ number_format($deposit->amount, 2) }}</td>
                <td>{{ $deposit->payment_mode }}</td>
                <td class="deposit-status">
                  <span class="badge {{ $deposit->status === 'pending' ? 'badge-pending' : 'badge-approved' }}">
                    {{ ucfirst($deposit->status) }}
                  </span>
                </td>
                <td>
                  @if($deposit->payment_proof)
                    <a href="{{ $deposit->payment_proof }}" target="_blank">View</a>
                  @else
                    N/A
                  @endif
                </td>
                <td class="deposit-action">
                  @if($deposit->status === 'pending')
                    <button class="btn btn-primary btn-sm" onclick="approveDeposit({{ $deposit->id }}, this)">Approve</button>
                  @else
                    -
                  @endif
                </td>
              </tr>
            @empty
              <tr><td colspan="5">No deposits found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- COPIED TRADERS HISTORY -->
  <div class="card-box">
    <h5>Copied Traders History</h5>
    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>S/N</th>
            <th>Trader</th>
            <th>Copied Since</th>
          </tr>
        </thead>
        <tbody>
          @forelse($user->copiedTraders->sortByDesc('created_at') as $copy)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $copy->trader->name ?? 'N/A' }}</td>
              <td>{{ $copy->created_at->format('d M Y, h:i A') }}</td>
            </tr>
          @empty
            <tr><td colspan="3">No copied traders found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>




   <!-- WITHDRAWAL HISTORY -->
  <div class="card-box">
    <h5>Withdrawal History</h5>
    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>Amount</th>
            <th>Method</th>
            <th>Wallet</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($user->withdrawals->sortByDesc('created_at') as $withdrawal)
            <tr id="withdrawal-row-{{ $withdrawal->id }}">
              <td>${{ number_format($withdrawal->amount, 2) }}</td>
              <td>{{ $withdrawal->receiving_mode }}</td>
              <td>{{ $withdrawal->address }}</td>
              <td class="withdrawal-status">
                <span class="badge {{ $withdrawal->status === 'pending' ? 'badge-pending' : 'badge-approved' }}">
                  {{ ucfirst($withdrawal->status) }}
                </span>
              </td>
              <td class="withdrawal-action">
                @if($withdrawal->status === 'pending')
                  <button class="btn btn-primary btn-sm" onclick="approveWithdrawal({{ $withdrawal->id }}, this)">
                    Approve
                  </button>
                @else
                  -
                @endif
              </td>
            </tr>
          @empty
            <tr><td colspan="5">No withdrawals found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>



    <!-- INVESTMENT HISTORY -->
    <div class="card-box">
      <h5>Investment History</h5>
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Plan</th>
              <th>Amount</th>
              <th>ROI</th>
              <th>Duration</th>
            </tr>
          </thead>
          <tbody>
            @forelse($user->investments->sortByDesc('created_at') as $investment)
              <tr>
                <td>{{ $investment->plan->name ?? '-' }}</td>
                <td>${{ number_format($investment->amount, 2) }}</td>
                <td>{{ $investment->plan->roi ?? '-' }}%</td>
                <td>{{ $investment->plan->duration ?? '-' }} days</td>
              </tr>
            @empty
              <tr><td colspan="4">No investments found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

    <!-- PROFIT MODAL -->
    <div id="profitModal">
      <div class="modal-box">
        <h5>Top Up Profit</h5>
        <input type="number" id="profitAmount" class="form-control" placeholder="Enter profit amount">
        <div class="mt-3 d-flex justify-content-end gap-2">
          <button class="btn btn-danger" onclick="closeProfitModal()">Cancel</button>
          <button class="btn btn-success" onclick="submitProfitTopUp({{ $user->id }})">Top Up</button>
        </div>
      </div>
    </div>

    <!-- DEBIT PROFIT MODAL -->
    <div id="debitProfitModal">
      <div class="modal-box">
        <h5>Debit Profit</h5>
        <input type="number" id="debitProfitAmount" class="form-control" placeholder="Enter profit debit amount">
        <div class="mt-3 d-flex justify-content-end gap-2">
          <button class="btn btn-secondary" onclick="closeDebitProfitModal()">Cancel</button>
          <button class="btn btn-danger" onclick="submitProfitDebit({{ $user->id }})">Debit Profit</button>
        </div>
      </div>
    </div>

    <!-- OTP MODAL -->
    <div id="otpModal">
      <div class="modal-box">
        <h5>Generate OTP</h5>
        <input type="number" id="otpInput" class="form-control" placeholder="Enter OTP or click generate">
        <div class="mt-3 d-flex justify-content-between">
          <button class="btn btn-danger" onclick="closeOtpModal()">Cancel</button>
          <button class="btn btn-dark" onclick="generateOtp()">Generate</button>
        </div>
      </div>
    </div>

    <!-- DEBIT MODAL -->
    <div id="debitModal">
      <div class="modal-box">
        <h5>Debit Account</h5>
        <input type="number" id="debitAmount" class="form-control" placeholder="Enter amount to debit">
        <div class="mt-3 d-flex justify-content-end gap-2">
          <button class="btn btn-secondary" onclick="closeDebitModal()">Cancel</button>
          <button class="btn btn-danger" onclick="submitDebit({{ $user->id }})">Debit</button>
        </div>
      </div>
    </div>
  </div>

  <!-- ADD BONUS MODAL -->
<div id="addBonusModal">
  <div class="modal-box">
    <h5>Add Bonus</h5>
    <input type="number" id="addBonusAmount" class="form-control" placeholder="Enter bonus amount">
    <div class="mt-3 d-flex justify-content-end gap-2">
      <button class="btn btn-secondary" onclick="closeAddBonusModal()">Cancel</button>
      <button class="btn btn-success" onclick="submitAddBonus({{ $user->id }})">Add Bonus</button>
    </div>
  </div>
</div>

<!-- REMOVE BONUS MODAL -->
<div id="removeBonusModal">
  <div class="modal-box">
    <h5>Debit Bonus</h5>
    <input type="number" id="removeBonusAmount" class="form-control" placeholder="Enter amount to remove">
    <div class="mt-3 d-flex justify-content-end gap-2">
      <button class="btn btn-secondary" onclick="closeRemoveBonusModal()">Cancel</button>
      <button class="btn btn-danger" onclick="submitRemoveBonus({{ $user->id }})">Remove Bonus</button>
    </div>
  </div>
</div>


  <script>
    document.querySelectorAll('.impersonate-form').forEach(form => {
        form.addEventListener('submit', function () {
            let btn = this.querySelector('.impersonate-btn');

            // Disable the button
            btn.disabled = true;

            // Hide normal text, show spinner + loading text
            btn.querySelector('.default-text').classList.add('d-none');
            btn.querySelector('.spinner-border').classList.remove('d-none');
            btn.querySelector('.loading-text').classList.remove('d-none');
        });
    });
  

    function openProfitModal() {
      document.getElementById('profitModal').style.display = 'flex';
    }

    function closeProfitModal() {
      document.getElementById('profitModal').style.display = 'none';
      document.getElementById('profitAmount').value = '';
    }

    function openDebitProfitModal() {
      document.getElementById('debitProfitModal').style.display = 'flex';
    }

    function closeDebitProfitModal() {
      document.getElementById('debitProfitModal').style.display = 'none';
      document.getElementById('debitProfitAmount').value = '';
    }


    function openOtpModal() {
      document.getElementById('otpModal').style.display = 'flex';
    }

    function closeOtpModal() {
      document.getElementById('otpModal').style.display = 'none';
      document.getElementById('otpInput').value = '';
    }

    function openDebitModal() {
      document.getElementById('debitModal').style.display = 'flex';
    }

    function closeDebitModal() {
      document.getElementById('debitModal').style.display = 'none';
      document.getElementById('debitAmount').value = '';
    }

    // OPEN / CLOSE Bonus Modals
    function openAddBonusModal() {
      document.getElementById('addBonusModal').style.display = 'flex';
    }
    function closeAddBonusModal() {
      document.getElementById('addBonusModal').style.display = 'none';
      document.getElementById('addBonusAmount').value = '';
    }

    function openRemoveBonusModal() {
      document.getElementById('removeBonusModal').style.display = 'flex';
    }
    function closeRemoveBonusModal() {
      document.getElementById('removeBonusModal').style.display = 'none';
      document.getElementById('removeBonusAmount').value = '';
    }

    function closeDebitProfitModal() {
      document.getElementById('debitProfitModal').style.display = 'none';
      document.getElementById('debitProfitAmount').value = '';
    }


    function submitProfitTopUp(userId) {
      const amount = parseFloat(document.getElementById('profitAmount').value);
      if (!amount || amount <= 0) return toastr.error("Enter a valid profit amount.");

      const btn = document.querySelector('#profitModal .btn-success');
      btn.disabled = true;
      btn.innerText = 'Processing...';

      fetch(`{{ route('admin.topup.profit', ['user' => $user->id]) }}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ amount })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          toastr.success(data.message);
          document.getElementById('user-balance').textContent = `$${parseFloat(data.new_balance).toFixed(2)}`;
          closeProfitModal();
        } else {
          toastr.error(data.message);
        }
      })
      .catch(() => toastr.error("Something went wrong."))
      .finally(() => {
        btn.disabled = false;
        btn.innerText = 'Top Up';
      });
    }

    function submitProfitDebit(userId) {
      const amount = parseFloat(document.getElementById('debitProfitAmount').value);
      if (!amount || amount <= 0) return toastr.error("Enter a valid debit amount.");

      const btn = document.querySelector('#debitProfitModal .btn-danger');
      btn.disabled = true;
      btn.innerText = 'Processing...';

      fetch(`{{ route('admin.debit.profit', ['user' => $user->id]) }}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ amount })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          toastr.success(data.message);
          document.getElementById('user-balance').textContent = `$${parseFloat(data.new_balance).toFixed(2)}`;
          closeDebitProfitModal();
        } else {
          toastr.error(data.message);
        }
      })
      .catch(() => toastr.error("Something went wrong."))
      .finally(() => {
        btn.disabled = false;
        btn.innerText = 'Debit Profit';
      });
    }


    function submitDebit(userId) {
      const amount = parseFloat(document.getElementById('debitAmount').value);
      if (!amount || amount <= 0) return toastr.error("Enter a valid debit amount.");

      const btn = document.querySelector('#debitModal .btn-danger');
      btn.disabled = true;
      btn.innerText = 'Processing...';

      fetch(`{{ route('admin.debit.balance', ['user' => $user->id]) }}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ amount })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          toastr.success(data.message);
          document.getElementById('user-balance').textContent = `$${parseFloat(data.new_balance).toFixed(2)}`;
          closeDebitModal();
        } else {
          toastr.error(data.message);
        }
      })
      .catch(() => toastr.error("Something went wrong."))
      .finally(() => {
        btn.disabled = false;
        btn.innerText = 'Debit';
      });
    }

    function generateOtp() {
      const otp = Math.floor(100000 + Math.random() * 900000);
      document.getElementById('otpInput').value = otp;

      const btn = document.querySelector('#otpModal .btn-dark');
      btn.disabled = true;
      btn.innerText = 'Sending...';

      fetch(`{{ route('admin.generate.otp', ['user' => $user->id]) }}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ otp })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          toastr.success('OTP generated and sent to user');
          document.getElementById('otp-value').textContent = otp;
          closeOtpModal();
        } else {
          toastr.error(data.message || 'Error occurred');
        }
      })
      .catch(() => toastr.error("Failed to generate OTP"))
      .finally(() => {
        btn.disabled = false;
        btn.innerText = 'Generate';
      });
    }

    function approveDeposit(depositId, btn) {
      btn.disabled = true;
      btn.innerText = 'Approving...';

      fetch(`/admin/deposits/${depositId}/approve`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          toastr.success(data.message);
          document.querySelector(`#deposit-row-${depositId} .deposit-status`).innerHTML = `<span class="badge badge-approved">Approved</span>`;
          document.querySelector(`#deposit-row-${depositId} .deposit-action`).innerHTML = '-';
        } else {
          toastr.error(data.message);
          btn.disabled = false;
          btn.innerText = 'Approve';
        }
      })
      .catch(() => {
        toastr.error('Something went wrong.');
        btn.disabled = false;
        btn.innerText = 'Approve';
      });
    }

    // SUBMIT Add Bonus
function submitAddBonus(userId) {
  const amount = parseFloat(document.getElementById('addBonusAmount').value);
  if (!amount || amount <= 0) return toastr.error("Enter a valid bonus amount.");

  const btn = document.querySelector('#addBonusModal .btn-success');
  btn.disabled = true;
  btn.innerText = 'Processing...';

  fetch(`/admin/users/${userId}/bonus`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
    },
    body: JSON.stringify({ amount })
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      toastr.success(data.message);
      document.getElementById('user-balance').textContent = `$${parseFloat(data.new_balance).toFixed(2)}`;
      closeAddBonusModal();
    } else {
      toastr.error(data.message);
    }
  })
  .catch(() => toastr.error("Something went wrong."))
  .finally(() => {
    btn.disabled = false;
    btn.innerText = 'Add Bonus';
  });
}

// SUBMIT Remove Bonus
function submitRemoveBonus(userId) {
  const amount = parseFloat(document.getElementById('removeBonusAmount').value);
  if (!amount || amount <= 0) return toastr.error("Enter a valid amount.");

  const btn = document.querySelector('#removeBonusModal .btn-danger');
  btn.disabled = true;
  btn.innerText = 'Processing...';

  fetch(`/admin/users/${userId}/bonus`, {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
    },
    body: JSON.stringify({ amount })
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      toastr.success(data.message);
      document.getElementById('user-balance').textContent = `$${parseFloat(data.new_balance).toFixed(2)}`;
      closeRemoveBonusModal();
    } else {
      toastr.error(data.message);
    }
  })
  .catch(() => toastr.error("Something went wrong."))
  .finally(() => {
    btn.disabled = false;
    btn.innerText = 'Remove Bonus';
  });
}

function approveWithdrawal(withdrawalId, btn) {
  btn.disabled = true;
  btn.innerText = 'Approving...';

  fetch(`/admin/withdrawals/${withdrawalId}/approve`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
      'Accept': 'application/json'
    }
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      toastr.success(data.message);
      document.querySelector(`#withdrawal-row-${withdrawalId} .withdrawal-status`).innerHTML =
        `<span class="badge badge-approved">Approved</span>`;
      document.querySelector(`#withdrawal-row-${withdrawalId} .withdrawal-action`).innerHTML = '-';
    } else {
      toastr.error(data.message);
      btn.disabled = false;
      btn.innerText = 'Approve';
    }
  })
  .catch(() => {
    toastr.error('Something went wrong.');
    btn.disabled = false;
    btn.innerText = 'Approve';
  });
}

  </script>
</x-admin>
