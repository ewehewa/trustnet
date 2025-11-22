<x-admin>
  <div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold">All Signals</h2>
      <a href="{{ route('admin.signals.create') }}">
        <button class="btn btn-primary">Create Signal</button>
      </a>
    </div>

    @if($signals->isEmpty())
      <div class="text-center py-5">
        <p class="text-secondary">No signals created yet.</p>
      </div>
    @else
      <div class="row g-4">
        @foreach($signals as $signal)
          <div class="col-12 col-sm-6 col-md-4 col-lg-3" id="signal-{{ $signal->id }}">
            <div class="card nft-card h-100 shadow-sm border-0">

              <div class="card-body text-center">

                <h5 class="card-title fw-bold mb-2">{{ $signal->name }}</h5>

                <p class="small text-info mb-2">
                  Price: ${{ number_format($signal->price, 2) }}
                </p>

                <p class="small text-secondary mb-1">
                  Category: {{ $signal->category }}
                </p>

                <p class="small text-secondary mb-1">
                  Duration: {{ $signal->duration ?? 'N/A' }} Weeks
                </p>

                <hr class="my-3">

                <p class="small text-dark mb-2">
                  <strong>Description One:</strong><br>
                  {{ $signal->description_1 }}
                </p>

                @if($signal->description_2)
                  <p class="small text-dark mb-2">
                    <strong>Description Two:</strong><br>
                    {{ $signal->description_2 }}
                  </p>
                @endif

                <p class="small mt-3">
                  Status:
                  <span class="badge {{ $signal->status ? 'bg-success' : 'bg-danger' }}">
                    {{ $signal->status ? 'Active' : 'Inactive' }}
                  </span>
                </p>

                <div class="d-flex justify-content-center gap-2 mt-3">
                  <a href="{{ route('admin.signals.edit', $signal->id) }}" class="btn btn-sm btn-warning">
                    Edit
                  </a>

                  <button class="btn btn-sm btn-danger" onclick="deleteSignal({{ $signal->id }})">
                    Delete
                  </button>
                </div>

              </div>

            </div>
          </div>
        @endforeach
      </div>
    @endif

  </div>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    .nft-card {
      background-color: #ffffff;
      border-radius: 14px;
      border: 1px solid #e5e7eb;
      padding-top: 10px;
      transition: 0.25s ease;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    .nft-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.12);
      border-color: #d1d5db;
    }
  </style>

  <script>
    function deleteSignal(id) {
      Swal.fire({
        title: 'Are you sure?',
        text: "This signal will be deleted permanently!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e63946',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(`/admin/signals/${id}/delete`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json'
            }
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              document.getElementById(`signal-${id}`).remove();
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
