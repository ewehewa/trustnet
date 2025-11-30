<x-admin>
  <style>
    .plans-wrapper {
      display: flex;
      justify-content: center;
    }

    .plans-container {
      max-width: 1000px;
      width: 100%;
      padding: 20px;
    }

    .plan-card {
      position: relative;
      background-color: #ffffff;
      border-radius: 16px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
      padding: 24px;
      margin-bottom: 24px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .plan-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    }

    .plan-title {
      font-size: 22px;
      font-weight: 700;
      color: #2d2d2d;
      margin-bottom: 12px;
    }

    .plan-detail {
      font-size: 15px;
      color: #555;
      margin-bottom: 6px;
    }

    .plan-detail strong {
      color: #222;
      font-weight: 600;
    }

    .plan-date {
      font-size: 13px;
      color: #888;
      margin-top: 12px;
    }

    .delete-btn {
      position: absolute;
      top: 16px;
      right: 16px;
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

    .edit-btn {
      position: absolute;
      top: 16px;
      right: 95px;
      background-color: #1d72b8;
      color: #fff;
      padding: 6px 12px;
      font-size: 13px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    .edit-btn:hover {
      background-color: #155d8b;
    }

    @media (max-width: 768px) {
      .plans-container {
        padding: 10px;
      }

      .plan-title {
        font-size: 18px;
      }

      .plan-detail {
        font-size: 14px;
      }

      .edit-btn {
        top: 12px;
        right: 75px;
        padding: 5px 10px;
        font-size: 12px;
      }

      .delete-btn {
        top: 12px;
        right: 12px;
        padding: 5px 10px;
        font-size: 12px;
      }
    }

    .no-plans-message {
      text-align: center;
      margin-top: 80px;
      color: #777;
    }

    .no-plans-message svg {
      color: #bbb;
    }

    .no-plans-message p {
      margin-bottom: 4px;
    }
  </style>

  <div class="container-fluid plans-wrapper">
    <div class="plans-container">
      <h4 class="mb-4 text-center fw-bold">Available Investment Plans</h4>

      @if ($plans->count())
        @foreach ($plans as $plan)
          <div class="plan-card" id="plan-{{ $plan->id }}">

            <!-- Edit Button -->
            <button
              class="edit-btn"
              onclick="window.location.href='{{ route('admin.plans.edit', ['id' => $plan->id]) }}'"
            >
              Edit
            </button>

            <!-- Delete Button -->
            <button
              class="delete-btn"
              onclick="deletePlan({{ $plan->id }}, '{{ route('admin.plans.destroy', ['id' => $plan->id]) }}')"
            >
              Delete
            </button>

            <div class="plan-title">{{ $plan->name }}</div>

            <div class="plan-detail"><strong>ROI:</strong> {{ $plan->roi }}%</div>
            <div class="plan-detail"><strong>Minimum Amount:</strong> ${{ number_format($plan->min_amount, 2) }}</div>
            <div class="plan-detail"><strong>Maximum Amount:</strong> ${{ number_format($plan->max_amount, 2) }}</div>
            <div class="plan-detail"><strong>Duration:</strong> {{ $plan->duration }} days</div>
            <div class="plan-date">Created: {{ $plan->created_at->format('d M Y') }}</div>
          </div>
        @endforeach
      @else
        <div class="no-plans-message">
          <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" class="mb-3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-6 0h6m-6 0v2m6-2v2m0-4h1a2 2 0 002-2v-5a2 2 0 00-2-2h-1m-6 0H7a2 2 0 00-2 2v5a2 2 0 002 2h1" />
          </svg>
          <p class="fs-5 fw-semibold">No investment plans available</p>
          <p class="text-muted">Please add a plan to get started.</p>
        </div>
      @endif
    </div>
  </div>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Delete Plan Script -->
  <script>
    function deletePlan(id, url) {
      Swal.fire({
        title: 'Are you sure?',
        text: "This plan will be deleted permanently!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e63946',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
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
              document.getElementById(`plan-${id}`).remove();
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

  <!-- Toastr Notifications -->
  <script>
    @if (session('success'))
      toastr.success("{{ session('success') }}");
    @endif
    @if (session('error'))
      toastr.error("{{ session('error') }}");
    @endif
  </script>

</x-admin>
