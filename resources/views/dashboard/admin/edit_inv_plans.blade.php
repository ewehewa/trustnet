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
        <h4 class="fw-bold">Edit Investment Plan</h4>
        <button class="btn-view" onclick="window.location='{{ route('admin.show.plans') }}'">
          View All Plans
        </button>
      </div>

      <form id="planForm">
        @csrf

        <div class="mb-3">
          <label class="form-label">Plan Name</label>
          <input type="text" class="form-control" name="name" value="{{ $plan->name }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">ROI (%)</label>
          <input type="number" step="0.01" class="form-control" name="roi" value="{{ $plan->roi }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Minimum Amount</label>
          <input type="number" class="form-control" name="min_amount" value="{{ $plan->min_amount }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Maximum Amount</label>
          <input type="number" class="form-control" name="max_amount" value="{{ $plan->max_amount }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Duration (days)</label>
          <input type="number" class="form-control" name="duration" value="{{ $plan->duration }}" required>
        </div>

        <div class="text-center">
          <button type="submit" class="btn-submit" id="submitBtn">
            <span class="btn-text">Update Plan</span>
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
    const form = document.getElementById('planForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoading = submitBtn.querySelector('.btn-loading');

    form.addEventListener('submit', function(e) {
      e.preventDefault();

      btnText.classList.add('d-none');
      btnLoading.classList.remove('d-none');
      submitBtn.disabled = true;

      const formData = new FormData(form);

      fetch("{{ route('admin.plans.update', $plan->id) }}", {
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
          toastr.success(data.message || 'Plan updated successfully!');
          window.location.href = "{{ route('admin.show.plans') }}";
        } else {
          toastr.error(data.message || 'Failed to update plan.');
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
