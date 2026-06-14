{{-- resources/views/content/backend/expense/index.blade.php --}}
@extends('layouts/contentNavbarLayout')

@section('title', 'Expenses - ' . $department->name)

@section('vendor-style')
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
@endsection

@section('vendor-script')
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('departments.index') }}">Departments</a></li>
      <li class="breadcrumb-item"><a
          href="{{ route('departments.categories.index', $department) }}">{{ $department->name }}</a></li>
      <li class="breadcrumb-item active">Expenses</li>
    </ol>
  </nav>

  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">
        Record Expense – {{ $department->name }}
        <span class="badge bg-label-success ms-2">PKR</span>
      </h5>
      <a href="{{ route('departments.categories.index', $department) }}" class="btn btn-sm btn-label-secondary">
        <i class="bx bx-arrow-back"></i> Back to Categories
      </a>
    </div>

    <div class="card-body">
      <form id="expenseForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" id="expense_id">

        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Category</label>
            <select class="form-select" name="category_id" id="category_id">
              <option value="">— Uncategorized —</option>
              @foreach ($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }} {{ $cat->unit ? "({$cat->unit})" : '' }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Amount <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text">Rs.</span>
              <input type="number" step="0.01" min="0.01" class="form-control" name="amount" id="amount"
                required placeholder="0.00">
            </div>
          </div>

          <div class="col-md-4">
            <label class="form-label">Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control" name="spent_at" id="spent_at" required
              value="{{ now()->format('Y-m-d') }}">
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-8">
            <label class="form-label">Note</label>
            <textarea class="form-control" name="note" id="note" rows="2"
              placeholder="e.g., Monthly electricity bill, Fuel for department vehicles..."></textarea>
          </div>
          <div class="col-md-4">
            <label class="form-label">Receipt (Optional)</label>
            <input type="file" class="form-control" name="receipt" id="receipt" accept="image/*,.pdf">
            <div class="form-text">JPG, PNG, PDF - Max 5MB</div>
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="bx bx-save me-1"></i> {{ request()->filled('id') ? 'Update' : 'Save' }} Expense
          </button>
          <button type="button" id="cancelBtn" class="btn btn-label-secondary ms-2" style="display:none;">
            <i class="bx bx-x me-1"></i> Cancel
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Expenses Table -->
  <div class="card">
    <div class="card-header">
      <h5 class="card-title mb-0">All Expenses ({{ $department->expenses()->count() }})</h5>
    </div>
    <div class="card-datatable table-responsive">
      <table class="table table-hover" id="expensesTable" style="width:100%">
        <thead class="table-light">
          <tr>
            <th width="60">#</th>
            <th>Category</th>
            <th class="text-end">Amount</th>
            <th>Date</th>
            <th>Note</th>
            <th class="text-center">Receipt</th>
            <th>By</th>
            <th width="120">Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
@endsection

@section('page-script')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('expenseForm');
      const tableEl = document.getElementById('expensesTable');
      const cancelBtn = document.getElementById('cancelBtn');
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      // Initialize DataTable
      const table = new DataTable(tableEl, {
        ajax: {
          url: '{{ route('departments.expenses.data', $department) }}',
          dataSrc: 'data',
          error: function(xhr, error, thrown) {
            console.error('DataTables Ajax Error:', xhr.responseText);
            Swal.fire({
              icon: 'error',
              title: 'Loading Error',
              text: 'Failed to load expenses. Please refresh the page.',
              footer: '<small>Check console for details</small>'
            });
          }
        },
        columns: [{
            data: 'id',
            width: '60px',
            className: 'text-center'
          },
          {
            data: 'category'
          },
          {
            data: 'amount',
            className: 'text-end fw-semibold'
          },
          {
            data: 'spent_at'
          },
          {
            data: 'note',
            width: '200px'
          },
          {
            data: 'receipt',
            orderable: false,
            className: 'text-center',
            render: data => data !== '—' ? data : ''
          },
          {
            data: 'creator'
          },
          {
            data: 'action',
            orderable: false,
            searchable: false,
            width: '120px'
          }
        ],
        order: [
          [3, 'desc']
        ],
        processing: true,
        serverSide: false,
        language: {
          processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
          emptyTable: 'No expenses recorded yet'
        }
      });
      // === FORM SUBMIT (Add & Update) ===
      form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Show loading
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Saving...';
        submitBtn.disabled = true;

        const formData = new FormData(form);

        try {
          const response = await fetch('{{ route('departments.expenses.store', $department) }}', {
            method: 'POST',
            body: formData,
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            }
          });

          // Always parse as JSON, even on errors
          const result = await response.json();

          if (response.ok && result.success) {
            Swal.fire({
              icon: 'success',
              title: 'Success!',
              text: result.message,
              timer: 2000,
              showConfirmButton: false
            });
            form.reset();
            document.getElementById('expense_id').value = '';
            cancelBtn.style.display = 'none';
            table.ajax.reload(null, false);
          } else {
            // Handle validation errors or other errors
            let errorMessage = result.message || 'Something went wrong';
            if (result.errors) {
              errorMessage = Object.values(result.errors).flat().join('<br>');
            }
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              html: errorMessage
            });
          }
        } catch (error) {
          console.error('Fetch error:', error);
          Swal.fire({
            icon: 'error',
            title: 'Network Error!',
            text: 'Please check your connection and try again.'
          });
        } finally {
          // Reset button
          submitBtn.innerHTML = originalText;
          submitBtn.disabled = false;
        }
      });

      // === EVENT DELEGATION FOR TABLE BUTTONS ===
      tableEl.addEventListener('click', async function(e) {
        // EDIT BUTTON
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
          e.preventDefault();
          const id = editBtn.dataset.id;

          try {
            const response = await fetch(`/departments/{{ $department->id }}/expenses/${id}/edit`, {
              headers: {
                'Accept': 'application/json'
              }
            });

            const data = await response.json();

            if (response.ok) {
              // Populate form
              document.getElementById('expense_id').value = data.id;
              document.getElementById('category_id').value = data.category_id || '';
              document.getElementById('amount').value = data.amount;
              document.getElementById('spent_at').value = data.spent_at;
              document.getElementById('note').value = data.note || '';

              // Show cancel button
              cancelBtn.style.display = 'inline-block';

              // Scroll to top
              document.querySelector('button[type="submit"]').textContent = 'Update Expense';
              window.scrollTo({
                top: 0,
                behavior: 'smooth'
              });
            } else {
              Swal.fire('Error!', data.error || 'Could not load expense', 'error');
            }
          } catch (error) {
            Swal.fire('Error!', 'Failed to load expense data', 'error');
          }
        }

        // DELETE BUTTON
        const delBtn = e.target.closest('.delete-btn');
        if (delBtn) {
          e.preventDefault();
          const id = delBtn.dataset.id;

          Swal.fire({
            title: 'Delete Expense?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
          }).then((result) => {
            if (result.isConfirmed) {
              fetch(`/departments/{{ $department->id }}/expenses/${id}`, {
                  method: 'DELETE',
                  headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                  }
                })
                .then(response => response.json())
                .then(data => {
                  if (data.success) {
                    Swal.fire('Deleted!', data.message, 'success');
                    table.ajax.reload(null, false);
                  } else {
                    Swal.fire('Error!', data.message || 'Failed to delete', 'error');
                  }
                })
                .catch(error => {
                  console.error('Delete error:', error);
                  Swal.fire('Error!', 'Failed to delete expense', 'error');
                });
            }
          });
        }
      });

      // === CANCEL BUTTON ===
      cancelBtn.addEventListener('click', function() {
        form.reset();
        document.getElementById('expense_id').value = '';
        document.querySelector('button[type="submit"]').textContent = 'Save Expense';
        this.style.display = 'none';
        document.getElementById('spent_at').value = '{{ now()->format('Y-m-d') }}';
      });

      // Set default date to today
      document.getElementById('spent_at').value = '{{ now()->format('Y-m-d') }}';
    });
  </script>
@endsection
