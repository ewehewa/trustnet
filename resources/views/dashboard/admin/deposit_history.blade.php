<x-admin>
  <style>
    .card-box {
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      padding: 25px;
      margin-bottom: 30px;
    }

    .deposit-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0 10px;
    }

    .deposit-table th,
    .deposit-table td {
      padding: 16px 20px;
      background-color: #fff;
    }

    .deposit-table tbody tr {
      box-shadow: 0 1px 5px rgba(0, 0, 0, 0.04);
      border-radius: 8px;
      transition: transform 0.2s ease;
    }

    .deposit-table tbody tr:hover {
      transform: scale(1.005);
    }

    .deposit-table td:first-child,
    .deposit-table th:first-child {
      border-top-left-radius: 10px;
      border-bottom-left-radius: 10px;
    }

    .deposit-table td:last-child,
    .deposit-table th:last-child {
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
    .badge-declined { background-color: #dc2626; }


    .btn-proof {
      background-color: #6c63ff;
      color: white;
    }

    .btn-proof:hover {
      background-color: #5848d7;
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
    }

    .btn-approve:hover {
      background-color: #1d4ed8;
    }

    .btn-decline {
  background-color: #dc2626;
  color: #fff;
  border: none;
  padding: 6px 12px;
  font-size: 13px;
  border-radius: 6px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
}

.btn-decline:hover {
  background-color: #b91c1c;
}

.d-flex.gap-2 {
  display: flex;
  gap: 8px;
}

@media (max-width: 768px) {
  .d-flex.gap-2 {
    flex-direction: column;
  }
}


    .spinner-border {
      width: 16px;
      height: 16px;
      border-width: 2px;
    }

    @media (max-width: 768px) {
      .deposit-table thead {
        display: none;
      }

      .deposit-table tbody tr {
        display: block;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 10px;
        overflow: hidden;
      }

      .deposit-table td {
        display: flex;
        justify-content: space-between;
        gap: 6px;
        padding: 12px 15px;
        font-size: 14px;
        border-bottom: 1px solid #f0f0f0;
      }

      .deposit-table td:last-child {
        border-bottom: none;
      }

      .deposit-table td::before {
        content: attr(data-label);
        font-weight: 600;
        color: #666;
      }
    }

    /* Pagination Styling */
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

    @media (max-width: 576px) {
      .pagination .page-link {
        padding: 6px 10px;
        font-size: 13px;
      }
    }
  </style>

  <div class="container-fluid">
    <div class="card-box">
      <h4 class="mb-4">All Deposit History</h4>

      @if($deposits->count())
        <div class="table-responsive">
          <table class="deposit-table">
            <thead>
              <tr>
                <th>#</th>
                <th>User</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Status</th>
                <th>Proof</th>
                <th>Date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($deposits as $index => $deposit)
              <tr id="deposit-{{ $deposit->id }}">
                <td data-label="#">{{ $deposits->firstItem() + $index }}</td>
                <td data-label="User">{{ $deposit->user->name ?? '-' }}</td>
                <td data-label="Amount">${{ number_format($deposit->amount, 2) }}</td>
                <td data-label="Method">{{ $deposit->payment_mode }}</td>
                <td data-label="Status">
                  <span class="badge {{ $deposit->status === 'pending' ? 'badge-pending' : 'badge-approved' }}" id="status-{{ $deposit->id }}">
                    {{ ucfirst($deposit->status) }}
                  </span>
                </td>
                <td data-label="Proof">
                  @if($deposit->payment_proof)
                    <a href="{{ $deposit->payment_proof }}" target="_blank" class="btn btn-sm btn-proof">View</a>
                  @else
                    N/A
                  @endif
                </td>
                <td data-label="Date">{{ $deposit->created_at->format('d M Y') }}</td>
                <td data-label="Action">
                  @if($deposit->status === 'pending')
                    <div class="d-flex flex-wrap gap-2">
                      <button
                        class="btn-approve"
                        onclick="approveDeposit(this)"
                        data-id="{{ $deposit->id }}"
                        data-url="{{ route('admin.deposits.approve', $deposit->id) }}"
                      >
                        <span class="btn-text">Approve</span>
                        <span class="btn-loading d-none">
                          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                          Approving...
                        </span>
                      </button>

                      <button
                        class="btn-decline"
                        onclick="declineDeposit(this)"
                        data-id="{{ $deposit->id }}"
                        data-url="{{ route('admin.deposits.decline', $deposit->id) }}"
                      >
                        <span class="btn-text">Decline</span>
                        <span class="btn-loading d-none">
                          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                          Declining...
                        </span>
                      </button>
                    </div>
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
          {{ $deposits->links('pagination::bootstrap-5') }}
        </div>
      @else
        <p class="text-muted text-center">No deposits found.</p>
      @endif
    </div>
  </div>

  <script>
    function approveDeposit(button) {
      const id = button.dataset.id;
      const url = button.dataset.url;

      const text = button.querySelector('.btn-text');
      const loading = button.querySelector('.btn-loading');

      // Show loader
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
          toastr.success(data.message || 'Deposit approved!');
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

function declineDeposit(button) {
  const id = button.dataset.id;
  const url = button.dataset.url;

  const text = button.querySelector('.btn-text');
  const loading = button.querySelector('.btn-loading');

  // Show loader
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
      status.textContent = 'Declined';
      status.classList.remove('badge-pending', 'badge-approved');
      status.classList.add('badge-declined'); // ✅ Use correct class name

      // Remove both buttons after action
      button.closest('.d-flex').remove();
      toastr.info(data.message || 'Deposit declined.');
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
