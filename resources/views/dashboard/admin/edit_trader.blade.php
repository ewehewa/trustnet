<x-admin>
  <style>
    .card-box {
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      padding: 30px;
      max-width: 700px;
      margin: 30px auto;
    }
    .form-label { font-weight: 600; color: #333; margin-bottom: 6px; }
    .form-control {
      padding: 10px 14px;
      border-radius: 8px;
      border: 1px solid #ddd;
      font-size: 14px;
      margin-bottom: 15px;
      width: 100%;
    }
    .btn-submit {
      background-color: #2563eb;
      color: white;
      border: none;
      padding: 10px 20px;
      font-weight: 600;
      border-radius: 8px;
      display: inline-flex;
      align-items: center;
      gap: 10px;
    }
    .btn-submit:hover { background-color: #1d4ed8; }
    .btn-view {
      background-color: #10b981;
      color: white;
      border: none;
      padding: 8px 16px;
      font-weight: 600;
      border-radius: 8px;
      float: right;
      margin-top: -10px;
    }
    .btn-view:hover { background-color: #059669; }
    .preview-img {
      width: 100px;
      height: 100px;
      border-radius: 8px;
      object-fit: cover;
      margin-bottom: 15px;
      border: 1px solid #ddd;
    }
  </style>

  <div class="container-fluid">
    <div class="card-box">

      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Edit Trader</h4>
        <button class="btn-view" onclick="window.location='{{ route('admin.traders.index') }}'">
          View All Traders
        </button>
      </div>

      <form id="traderForm" enctype="multipart/form-data">
        @csrf

        <!-- NAME -->
        <div class="mb-3">
          <label class="form-label">Trader Name</label>
          <input type="text" class="form-control" name="name" value="{{ $trader->name }}" required>
        </div>

        <!-- CURRENT IMAGE -->
        <div class="mb-3">
          <label class="form-label">Current Picture</label><br>
          <img src="{{ $trader->picture }}" class="preview-img">
        </div>

        <!-- NEW IMAGE -->
        <div class="mb-3">
          <label class="form-label">Upload New Picture (optional)</label>
          <input type="file" class="form-control" name="picture" accept="image/*">
        </div>

        <div class="mb-3">
          <label class="form-label">Average Return (%)</label>
          <input type="number" step="0.01" class="form-control" name="average_return"
                 value="{{ $trader->average_return }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Followers</label>
          <input type="number" class="form-control" name="followers"
                 value="{{ $trader->followers }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Profit Share (%)</label>
          <input type="number" class="form-control" step="0.1" name="profit_share"
                 value="{{ $trader->profit_share }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Win Rate (%)</label>
          <input type="number" class="form-control" step="0.1" name="win_rate"
                 value="{{ $trader->win_rate }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Total Profit</label>
          <input type="number" class="form-control" name="total_profit"
                 value="{{ $trader->total_profit }}" required>
        </div>

        <div class="text-center">
          <button type="submit" class="btn-submit" id="submitBtn">
            <span class="btn-text">Update Trader</span>
            <span class="btn-loading d-none">
              <span class="spinner-border spinner-border-sm" role="status"></span>
              Updating...
            </span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    const form = document.getElementById('traderForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoading = submitBtn.querySelector('.btn-loading');

    form.addEventListener('submit', function(e) {
      e.preventDefault();

      btnText.classList.add('d-none');
      btnLoading.classList.remove('d-none');
      submitBtn.disabled = true;

      const formData = new FormData(form);

      fetch("{{ route('admin.traders.update', $trader->id) }}", {
        method: "POST",
        headers: { "Accept": "application/json" },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          toastr.success(data.message || 'Trader updated successfully!');
          window.location.href = "{{ route('admin.traders.index') }}";
        } else {
          toastr.error(data.message || 'Failed to update trader.');
        }
      })
      .catch(() => toastr.error('Network error.'))
      .finally(() => {
        btnText.classList.remove('d-none');
        btnLoading.classList.add('d-none');
        submitBtn.disabled = false;
      });
    });
  </script>

</x-admin>
