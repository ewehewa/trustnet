<x-admin>
  <style>
    .card-box {
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      padding: 25px;
      margin-bottom: 30px;
    }

    .withdrawal-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0 10px;
    }

    .withdrawal-table th,
    .withdrawal-table td {
      padding: 16px 20px;
      background-color: #fff;
    }

    .withdrawal-table tbody tr {
      box-shadow: 0 1px 5px rgba(0, 0, 0, 0.04);
      border-radius: 8px;
      transition: transform 0.2s ease;
    }

    .withdrawal-table tbody tr:hover {
      transform: scale(1.005);
    }

    .withdrawal-table td:first-child,
    .withdrawal-table th:first-child {
      border-top-left-radius: 10px;
      border-bottom-left-radius: 10px;
    }

    .withdrawal-table td:last-child,
    .withdrawal-table th:last-child {
      border-top-right-radius: 10px;
      border-bottom-right-radius: 10px;
    }

    .badge {
      padding: 5px 10px;
      font-size: 13px;
      border-radius: 5px;
      color: #fff;
    }

    .badge-approved { background-color: #10b981; }
    .badge-pending { background-color: #f59e0b; }
    .badge-rejected { background-color: #ef4444; }

    @media (max-width: 768px) {
      .withdrawal-table thead {
        display: none;
      }

      .withdrawal-table tbody tr {
        display: block;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 10px;
        overflow: hidden;
      }

      .withdrawal-table td {
        display: flex;
        justify-content: space-between;
        gap: 6px;
        padding: 12px 15px;
        font-size: 14px;
        border-bottom: 1px solid #f0f0f0;
      }

      .withdrawal-table td:last-child {
        border-bottom: none;
      }

      .withdrawal-table td::before {
        content: attr(data-label);
        font-weight: 600;
        color: #666;
      }
    }

    .pagination-wrapper {
      display: flex;
      justify-content: center;
    }

    .pagination {
      flex-wrap: wrap;
      gap: 6px;
    }

    .pagination .page-item {
      margin: 0 2px;
    }

    .pagination .page-link {
      color: #333;
      border-radius: 6px;
      padding: 6px 12px;
      border: 1px solid #ddd;
      transition: all 0.2s ease-in-out;
    }

    .pagination .page-link:hover {
      background-color: #f0f0f0;
      color: #000;
    }

    .pagination .active .page-link {
      background-color: #2563eb;
      border-color: #2563eb;
      color: #fff;
    }

    .pagination .disabled .page-link {
      color: #bbb;
      background-color: #f8f9fa;
      cursor: not-allowed;
    }
    .btn-approve {
  background-color: #2563eb;
  color: #fff;
  border: none;
  padding: 6px 12px;
  font-size: 13px;
  border-radius: 6px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: background-color 0.2s ease-in-out;
}

.btn-approve:hover {
  background-color: #1d4ed8;
}

.btn-approve:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.spinner-border {
  width: 16px;
  height: 16px;
  border-width: 2px;
}

  </style>

  <div class="container-fluid">
    <div class="card-box">
      <h4 class="mb-4">Withdrawal History</h4>

      @if($withdrawals->count())
        <div class="table-responsive">
          <table class="withdrawal-table">
            <thead>
              <tr>
                <th>#</th>
                <th>User</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th> <!-- ADDED Action column -->
              </tr>
            </thead>
            <tbody>
              @foreach ($withdrawals as $index => $withdrawal)
                <tr id="withdrawal-{{ $withdrawal->id }}">
                  <td data-label="#">{{ $withdrawals->firstItem() + $index }}</td>
                  <td data-label="User">{{ $withdrawal->user->name ?? '-' }}</td>
                  <td data-label="Amount">${{ number_format($withdrawal->amount, 2) }}</td>
                  <td data-label="Method">{{ $withdrawal->receiving_mode ?? 'N/A' }}</td>
                  <td data-label="Status">
                    <span class="badge 
                      {{ $withdrawal->status === 'pending' ? 'badge-pending' : 
                        ($withdrawal->status === 'approved' ? 'badge-approved' : 'badge-rejected') }}"
                      id="status-{{ $withdrawal->id }}">
                      {{ ucfirst($withdrawal->status) }}
                    </span>
                  </td>
                  <td data-label="Date">{{ $withdrawal->created_at->format('d M Y') }}</td>
                  <td data-label="Action">
                    @if($withdrawal->status === 'pending')
                      <button
                        class="btn-approve"
                        onclick="approveWithdrawal(this)"
                        data-id="{{ $withdrawal->id }}"
                        data-url="{{ route('admin.withdrawals.approve', $withdrawal->id) }}"
                      >
                        <span class="btn-text">Approve</span>
                        <span class="btn-loading d-none">
                          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                          Approving...
                        </span>
                      </button>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>

          </table>
        </div>

        <div class="pagination-wrapper mt-4">
          {{ $withdrawals->links('pagination::bootstrap-5') }}
        </div>
      @else
        <p class="text-muted text-center">No withdrawals found.</p>
      @endif
    </div>
  </div>

  <script>
    function approveWithdrawal(button) {
      const id = button.dataset.id;
      const url = button.dataset.url;

      const text = button.querySelector('.btn-text');
      const loading = button.querySelector('.btn-loading');

      text.classList.add('d-none');
      loading.classList.remove('d-none');
      button.disabled = true;

      fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          const status = document.getElementById(`status-${id}`);
          status.textContent = 'Approved';
          status.classList.remove('badge-pending');
          status.classList.add('badge-approved');

          button.remove();
          toastr.success(data.message || 'Withdrawal approved!');
        } else {
          toastr.error(data.message || 'Something went wrong.');
          resetBtn();
        }
      })
      .catch(() => {
        toastr.error('Network error.');
        resetBtn();
      });

      function resetBtn() {
        text.classList.remove('d-none');
        loading.classList.add('d-none');
        button.disabled = false;
      }
    }
  </script>

</x-admin>
