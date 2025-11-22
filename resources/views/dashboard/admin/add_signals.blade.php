<x-admin>
  <style>
    .card-box {
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      padding: 30px;
      max-width: 700px;
      margin: 30px auto;
    }
    .form-label {
      font-weight: 600;
      color: #333;
      margin-bottom: 6px;
    }
    .form-control, .form-select {
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
      transition: background-color 0.3s ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
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
      transition: background-color 0.3s ease;
      font-size: 14px;
      float: right;
      margin-top: -10px;
    }
    .btn-view:hover { background-color: #059669; }
    .spinner-border {
      width: 16px; height: 16px; border-width: 2px;
    }
  </style>

  <div class="container-fluid">
    <div class="card-box">

      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Create New Signal</h4>
        <button class="btn-view" onclick="window.location='{{ route('admin.signals.index') }}'">
          View All Signals
        </button>
      </div>

      <form id="signalForm">
        @csrf

        <div class="mb-3">
          <label class="form-label">Signal Name</label>
          <input type="text" name="name" class="form-control" placeholder="Enter signal name" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Price</label>
          <input type="number" name="price" class="form-control" placeholder="Enter price" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Category</label>
          <input type="text" name="category" class="form-control" placeholder="General trading signal">
        </div>

        <div class="mb-3">
          <label class="form-label">Description Block 1</label>
          <textarea name="description_1" class="form-control" rows="3" placeholder="Enter description..."></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Description Block 2</label>
          <textarea name="description_2" class="form-control" rows="3" placeholder="Enter additional description..."></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Duration</label>
          <input type="text" name="duration" class="form-control" placeholder="E.g. 5 Weeks">
        </div>

        <div class="mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="1" selected>Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>

        <div class="text-center">
          <button type="submit" class="btn-submit" id="submitBtn">
            <span class="btn-text">Create Signal</span>
            <span class="btn-loading d-none">
              <span class="spinner-border spinner-border-sm" role="status"></span>
              Saving...
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

    form.addEventListener('submit', async function(e) {
      e.preventDefault();

      btnText.classList.add('d-none');
      btnLoading.classList.remove('d-none');
      submitBtn.disabled = true;

      const formData = new FormData(form);

      try {
        const res = await fetch("{{ route('admin.signals.store') }}", {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
          },
          body: formData
        });

        const data = await res.json();

        if (data.success) {
          toastr.success(data.message || 'Signal created successfully!');
          setTimeout(() => {
            window.location.href = "{{ route('admin.signals.index') }}";
          }, 1500);
        } else {
          toastr.error(data.message || 'Failed to create signal.');
        }

      } catch (err) {
        toastr.error('Network error.');
      } finally {
        btnText.classList.remove('d-none');
        btnLoading.classList.add('d-none');
        submitBtn.disabled = false;
      }
    });
  </script>
</x-admin>
