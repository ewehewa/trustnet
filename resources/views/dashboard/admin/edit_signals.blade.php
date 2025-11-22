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
    .form-label { 
      font-weight: 600; 
      color: #333; 
      margin-bottom: 6px; 
    }
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
      align-items:center; 
      gap:10px; 
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
    .spinner-border { width: 16px; height: 16px; border-width: 2px; }
    @media (max-width: 576px) { .card-box { padding: 20px; } }
  </style>

  <div class="container-fluid">
    <div class="card-box">

      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Edit Signal</h4>
        <button class="btn-view" onclick="window.location='{{ route('admin.signals.index') }}'">
          View All Signals
        </button>
      </div>

      <form id="signalForm">
        @csrf

        <div class="mb-3">
          <label class="form-label">Signal Name</label>
          <input type="text" class="form-control" name="name" value="{{ $signal->name }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Price ($)</label>
          <input type="number" step="0.01" class="form-control" name="price" value="{{ $signal->price }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Category</label>
          <select class="form-control" name="category" required>
            <option value="forex" {{ $signal->category=='forex'?'selected':'' }}>Forex</option>
            <option value="crypto" {{ $signal->category=='crypto'?'selected':'' }}>Crypto</option>
            <option value="stocks" {{ $signal->category=='stocks'?'selected':'' }}>Stocks</option>
            <option value="indices" {{ $signal->category=='indices'?'selected':'' }}>Indices</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Duration</label>
          <input type="text" class="form-control" name="duration" value="{{ $signal->duration }}">
        </div>

        <div class="mb-3">
          <label class="form-label">Description 1</label>
          <textarea class="form-control" rows="3" name="description_1" required>{{ $signal->description_1 }}</textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Description 2 (Optional)</label>
          <textarea class="form-control" rows="3" name="description_2">{{ $signal->description_2 }}</textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Status</label>
          <select class="form-control" name="status" required>
            <option value="1" {{ $signal->status ? 'selected' : '' }}>Active</option>
            <option value="0" {{ !$signal->status ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>

        <div class="text-center">
          <button type="submit" class="btn-submit" id="submitBtn">
            <span class="btn-text">Update Signal</span>
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
    const form = document.getElementById('signalForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoading = submitBtn.querySelector('.btn-loading');

    form.addEventListener('submit', function(e) {
      e.preventDefault();

      btnText.classList.add('d-none');
      btnLoading.classList.remove('d-none');
      submitBtn.disabled = true;

      const formData = new FormData(form);

      fetch("{{ route('admin.signals.update', $signal->id) }}", {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          toastr.success(data.message || 'Signal updated successfully!');
          window.location.href = "{{ route('admin.signals.index') }}";
        } else {
          toastr.error(data.message || 'Failed to update signal.');
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
