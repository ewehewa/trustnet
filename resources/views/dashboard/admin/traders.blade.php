<x-admin>
  <style>
    .trader-wrapper {
      display: flex;
      justify-content: center;
    }

    .trader-container {
      max-width: 1000px;
      width: 100%;
      padding: 20px;
    }

    .header-section {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .add-btn {
      background-color: #1d72b8;
      color: #fff;
      padding: 10px 18px;
      border: none;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .add-btn:hover {
      background-color: #155d91;
    }

    .trader-card {
      position: relative;
      background-color: #ffffff;
      border-radius: 16px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
      padding: 24px;
      margin-bottom: 24px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .trader-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    }

    .trader-title {
      font-size: 22px;
      font-weight: 700;
      color: #2d2d2d;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
    }

    .trader-title img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      margin-right: 10px;
    }

    .trader-detail {
      font-size: 15px;
      color: #555;
      margin-bottom: 6px;
    }

    .trader-detail strong {
      color: #222;
      font-weight: 600;
    }

    .action-buttons {
      position: absolute;
      top: 16px;
      right: 16px;
      display: flex;
      gap: 8px;
    }

    .edit-btn {
      background-color: #1d72b8;
      color: #fff;
      padding: 6px 12px;
      font-size: 13px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    .edit-btn:hover {
      background-color: #155d91;
    }

    .delete-btn {
      background-color: #e63946;
      color: #fff;
      padding: 6px 12px;
      font-size: 13px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    .delete-btn:hover {
      background-color: #c12736;
    }

    .no-traders-message {
      text-align: center;
      margin-top: 80px;
      color: #777;
    }
  </style>

  <div class="container-fluid trader-wrapper">
    <div class="trader-container">

      <!-- Header Section -->
      <div class="header-section">
        <h4 class="fw-bold">All Traders</h4>
        <button class="add-btn" onclick="window.location='{{ route('admin.traders.create') }}'">
          + New Trader
        </button>
      </div>

      @if ($traders->count())
        @foreach ($traders as $trader)
          <div class="trader-card" id="trader-{{ $trader->id }}">

            <!-- Edit + Delete Buttons -->
            <div class="action-buttons">
              <button class="edit-btn" onclick="window.location='{{ route('admin.traders.edit', $trader->id) }}'">
                Edit
              </button>
              <button
                class="delete-btn"
                onclick="deleteTrader({{ $trader->id }}, '{{ route('admin.traders.destroy', ['id' => $trader->id]) }}')"
              >
                Delete
              </button>
            </div>

            <div class="trader-title">
              <img src="{{ $trader->picture }}" alt="{{ $trader->name }}">
              {{ $trader->name }}
            </div>

            <div class="trader-detail"><strong>Average Return:</strong> {{ $trader->average_return }}%</div>
            <div class="trader-detail"><strong>Followers:</strong> {{ $trader->followers }}</div>
            <div class="trader-detail"><strong>Profit Share:</strong> {{ $trader->profit_share }}%</div>
            <div class="trader-detail"><strong>Win Rate:</strong> {{ $trader->win_rate }}%</div>
            <div class="trader-detail"><strong>Total Profit:</strong> ${{ number_format($trader->total_profit, 2) }}</div>
          </div>
        @endforeach
      @else
        <div class="no-traders-message">
          <p class="fs-5 fw-semibold">No traders available</p>
          <p class="text-muted">Please add a trader to get started.</p>
        </div>
      @endif

    </div>
  </div>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Delete Trader Script -->
  <script>
    function deleteTrader(id, url) {
      Swal.fire({
        title: 'Are you sure?',
        text: "This trader will be deleted permanently!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e63946',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete!',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(url, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json'
            }
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              document.getElementById(`trader-${id}`).remove();
              toastr.success(data.message);
            } else {
              toastr.error(data.message || 'Unable to delete.');
            }
          })
          .catch(() => toastr.error('Something went wrong.'));
        }
      });
    }
  </script>

</x-admin>
