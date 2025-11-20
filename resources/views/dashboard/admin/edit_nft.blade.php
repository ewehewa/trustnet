edit_nft<x-admin>
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
    .form-control { padding: 10px 14px; border-radius: 8px; border: 1px solid #ddd; font-size: 14px; margin-bottom: 15px; width: 100%; }
    .btn-submit { background-color: #2563eb; color: white; border: none; padding: 10px 20px; font-weight: 600; border-radius: 8px; display: inline-flex; align-items:center; gap:10px; }
    .btn-submit:hover { background-color: #1d4ed8; }
    .btn-view { background-color: #10b981; color: white; border: none; padding: 8px 16px; font-weight: 600; border-radius: 8px; float: right; margin-top: -10px; }
    .btn-view:hover { background-color: #059669; }
    .spinner-border { width: 16px; height: 16px; border-width: 2px; }
    @media (max-width: 576px) { .card-box { padding: 20px; } }
  </style>

  <div class="container-fluid">
    <div class="card-box">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Edit NFT</h4>
        <button class="btn-view" onclick="window.location='{{ route('admin.nfts.index') }}'">
          View All NFTs
        </button>
      </div>

      <form id="nftForm" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
          <label for="name" class="form-label">NFT Name</label>
          <input type="text" class="form-control" id="name" name="name" value="{{ $nft->name }}" required>
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">Description</label>
          <textarea class="form-control" id="description" name="description">{{ $nft->description }}</textarea>
        </div>

        <div class="mb-3">
          <label for="price" class="form-label">Price (ETH)</label>
          <input type="number" step="0.0001" class="form-control" id="price" name="price" value="{{ $nft->price }}" required>
        </div>

        <div class="mb-3">
          <label for="category" class="form-label">Category</label>
          <select class="form-control" name="category" id="category" required>
            <option value="art" {{ $nft->category=='art'?'selected':'' }}>Art</option>
            <option value="collectibles" {{ $nft->category=='collectibles'?'selected':'' }}>Collectibles</option>
            <option value="music" {{ $nft->category=='music'?'selected':'' }}>Music</option>
            <option value="photography" {{ $nft->category=='photography'?'selected':'' }}>Photography</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="image_file" class="form-label">NFT Image</label>
          <input type="file" class="form-control" id="image_file" name="image_file" accept="image/*">
          <img src="{{ $nft->image_url }}" alt="NFT Image" class="mt-2" style="height:150px;">
        </div>

        <div class="mb-3">
          <label class="form-label">On Sale</label>
          <select class="form-control" name="on_sale" required>
            <option value="1" {{ $nft->on_sale?'selected':'' }}>Yes</option>
            <option value="0" {{ !$nft->on_sale?'selected':'' }}>No</option>
          </select>
        </div>

        <div class="text-center">
          <button type="submit" class="btn-submit" id="submitBtn">
            <span class="btn-text">Update NFT</span>
            <span class="btn-loading d-none">
              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
              Updating...
            </span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    const form = document.getElementById('nftForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoading = submitBtn.querySelector('.btn-loading');

    form.addEventListener('submit', function(e) {
      e.preventDefault();

      btnText.classList.add('d-none');
      btnLoading.classList.remove('d-none');
      submitBtn.disabled = true;

      const formData = new FormData(form);

      fetch("{{ route('admin.nfts.update', $nft->id) }}", {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json'
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          toastr.success(data.message || 'NFT updated successfully!');
          window.location.href = "{{ route('admin.nfts.index') }}";
        } else {
          toastr.error(data.message || 'Failed to update NFT.');
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
