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

    .btn-submit:hover {
      background-color: #1d4ed8;
    }

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

    .btn-view:hover {
      background-color: #059669;
    }

    .spinner-border {
      width: 16px;
      height: 16px;
      border-width: 2px;
    }

    @media (max-width: 576px) {
      .card-box {
        padding: 20px;
      }
    }
  </style>

  <div class="container-fluid">
    <div class="card-box">
      <!-- Header with View All NFTs button -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Mint New NFT</h4>
        <button class="btn-view" onclick="window.location='{{ route('admin.nfts.index') }}'">
          View All NFTs
        </button>
      </div>

      <form id="adminNftForm" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
          <label for="name" class="form-label">NFT Name</label>
          <input type="text" class="form-control" id="name" name="name" placeholder="NFT title" required>
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">Description</label>
          <textarea class="form-control" id="description" name="description" rows="3" placeholder="NFT description"></textarea>
        </div>

        <div class="mb-3">
          <label for="price" class="form-label">Price (ETH)</label>
          <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="NFT price" required>
        </div>

        <div class="mb-3">
          <label for="category" class="form-label">Category</label>
          <select class="form-select" id="category" name="category" required>
            <option value="">Select Category</option>
            <option value="art">Art</option>
            <option value="collectibles">Collectibles</option>
            <option value="music">Music</option>
            <option value="photography">Photography</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="image_file" class="form-label">NFT Image</label>
          <input type="file" class="form-control" id="image_file" name="image_file" accept="image/*,image/gif" required>
        </div>

        <div class="text-center">
          <button type="submit" class="btn-submit" id="submitBtn">
            <span class="btn-text">Mint NFT</span>
            <span class="btn-loading d-none">
              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
              Minting...
            </span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
  const form = document.getElementById('adminNftForm');
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
      const res = await fetch("{{ route('admin.nfts.mint') }}", {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json'
        },
        body: formData
      });

      const data = await res.json();

      if (data.success) {
        toastr.success(data.message || 'NFT minted successfully!');
        setTimeout(() => {
          window.location.href = "{{ route('admin.nfts.index') }}";
        }, 1500);
      } else {
        toastr.error(data.message || 'Failed to mint NFT.');
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
