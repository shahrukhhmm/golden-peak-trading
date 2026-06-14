@extends('layouts/contentNavbarLayout')

@section('title', 'Categories - ' . $department->name)

@section('vendor-style')
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
@endsection

@section('vendor-script')
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('departments.index') }}">Departments</a></li>
      <li class="breadcrumb-item active">{{ $department->name }}</li>
      <li class="breadcrumb-item active">Categories</li>
    </ol>
  </nav>

  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">
            Categories in <strong>{{ $department->name }}</strong>
            <span class="badge bg-label-info ms-2">{{ $department->categories_count }} total</span>
          </h5>
          <a href="{{ route('departments.index') }}" class="btn btn-sm btn-label-secondary">
            Back to Departments
          </a>
        </div>

        <div class="card-body">
          <form id="categoryForm">
            @csrf
            <input type="hidden" name="id" id="category_id">
            <input type="hidden" name="department_id" value="{{ $department->id }}">

            <div class="row g-3">
              <div class="col-md-5">
                <label class="form-label">Category Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" id="name" required
                  placeholder="e.g., Fuel, Salary, Fuel, Maintenance">
              </div>
              <div class="col-md-3">
                <label class="form-label">Unit (optional)</label>
                <input type="text" class="form-control" name="unit" id="unit"
                  placeholder="per kg, per liter, per day">
              </div>
              <div class="col-md-4">
                <label class="form-label">Notes</label>
                <input type="text" class="form-control" name="notes" id="notes"
                  placeholder="e.g., Monthly driver salary">
              </div>
            </div>

            <div class="mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="bx bx-save me-1"></i>
                {{ request()->route('category') ? 'Update' : 'Add' }} Category
              </button>
              <button type="button" id="cancelBtn" class="btn btn-label-secondary ms-2" style="display:none;">
                Cancel
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Categories Table -->
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">All Categories ({{ $department->categories_count }})</h5>
        </div>
        <div class="card-datatable table-responsive">
          <table class="table table-hover" id="categoriesTable" style="width:100%">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Unit</th>
                <th>Notes</th>
                <th>Created</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('categoryForm');
      const tableEl = document.getElementById('categoriesTable');
      const cancelBtn = document.getElementById('cancelBtn');

      // Get CSRF token once (correct way)
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      const table = new DataTable(tableEl, {
        ajax: {
          url: '{{ route('departments.categories.data', $department) }}',
          dataSrc: 'data'
        },
        columns: [{
            data: 'id',
            width: '60px'
          },
          {
            data: 'name'
          },
          {
            data: 'unit',
            orderable: false
          },
          {
            data: 'notes',
            orderable: false
          },
          {
            data: 'created_at'
          },
          {
            data: 'action',
            orderable: false,
            searchable: false,
            width: '100px'
          }
        ],
        processing: true,
        language: {
          processing: '<div class="spinner-border text-primary"></div>'
        }
      });

      // === SAVE (Add & Update) ===
      form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(form);

        try {
          const response = await fetch('{{ route('departments.categories.store', $department) }}', {
            method: 'POST',
            body: formData,
            headers: {
              'X-CSRF-TOKEN': csrfToken, // THIS WAS MISSING/WRONG
              'Accept': 'application/json'
            }
          });

          const result = await response.json();

          if (response.ok) {
            Swal.fire('Success!', result.message, 'success');
            form.reset();
            document.getElementById('category_id').value = '';
            cancelBtn.style.display = 'none';
            table.ajax.reload(null, false);
          } else {
            let msg = 'Validation failed!';
            if (result.errors) {
              msg = Object.values(result.errors).flat().join('<br>');
            }
            Swal.fire('Error!', msg, 'error');
          }
        } catch (err) {
          Swal.fire('Error!', 'Network error', 'error');
        }
      });

      // === EDIT ===
      tableEl.addEventListener('click', async function(e) {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
          const id = editBtn.dataset.id;
          const res = await fetch(`/departments/{{ $department->id }}/categories/${id}/edit`);
          const data = await res.json();

          document.getElementById('category_id').value = data.id;
          document.getElementById('name').value = data.name;
          document.getElementById('unit').value = data.unit || '';
          document.getElementById('notes').value = data.notes || '';
          cancelBtn.style.display = 'inline-block';
          window.scrollTo({
            top: 0,
            behavior: 'smooth'
          });
        }

        // === DELETE ===
        const delBtn = e.target.closest('.delete-btn');
        if (delBtn) {
          const id = delBtn.dataset.id;
          Swal.fire({
            title: 'Delete category?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            if (result.isConfirmed) {
              fetch(`{{ url('departments') }}/{{ $department->id }}/categories/${id}`, {
                method: 'DELETE',
                headers: {
                  'X-CSRF-TOKEN': csrfToken
                }
              }).then(() => {
                Swal.fire('Deleted!', '', 'success');
                table.ajax.reload(null, false);
              });
            }
          });
        }
      });

      // Cancel button
      cancelBtn.addEventListener('click', () => {
        form.reset();
        document.getElementById('category_id').value = '';
        cancelBtn.style.display = 'none';
      });
    });
  </script>
@endsection
