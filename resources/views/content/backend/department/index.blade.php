{{-- resources/views/content/backend/department/index.blade.php --}}
@extends('layouts/contentNavbarLayout')

@section('title', 'Departments - Dashboard')

@section('vendor-style')
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
@endsection

@section('vendor-script')
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
  <div class="card mb-4">
    <h5 class="card-header">Departments Management</h5>
    <div class="card-body">
      <form id="departmentForm">
        @csrf
        <input type="hidden" name="id" id="department_id">

        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Key <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="key" id="key" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="name" id="name" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" id="description" rows="2"></textarea>
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">Save Department</button>
          <button type="button" id="cancelBtn" class="btn btn-label-secondary ms-2" style="display:none;">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Departments Table -->
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">All Departments</h5>
      <span class="badge bg-label-primary">Total: <span id="totalDepts">0</span></span>
    </div>
    <div class="card-datatable table-responsive">
      <table class="table table-hover" id="departmentsTable" style="width:100%">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Key</th>
            <th>Name</th>
            <th>Description</th>
            <th>Created</th>
            <th class="text-center">Actions</th>
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
      const form = document.getElementById('departmentForm');
      const tableElement = document.getElementById('departmentsTable');
      const cancelBtn = document.getElementById('cancelBtn');
      const totalSpan = document.getElementById('totalDepts');

      const table = new DataTable(tableElement, {
        ajax: {
          url: '{{ route('departments.data') }}',
          dataSrc: 'data'
        },
        columns: [{
            data: 'id',
            width: '60px'
          },
          {
            data: 'key'
          },
          {
            data: 'name',
            className: 'fw-semibold'
          },
          {
            data: 'description',
            render: data => data ? (data.length > 70 ? data.substr(0, 70) + '...' : data) :
              '<em class="text-muted">—</em>'
          },
          {
            data: 'created_at',
            render: data => new Date(data).toLocaleDateString('en-GB')
          },
          {
            data: null,
            orderable: false,
            searchable: false,
            width: '380px',
            className: 'text-center',
            render: function(data) {
              return `
            <div class="d-flex gap-2 justify-content-center flex-wrap">

              <!-- Edit -->
              <button class="btn btn-sm btn-primary edit-btn" data-id="${data.id}" title="Edit">
                <i class="bx bx-edit"></i>
              </button>

              <!-- Categories -->
              <a href="/departments/${data.id}/categories" class="btn btn-sm btn-secondary" title="Categories">
                <i class="bx bx-category"></i>
              </a>

              <!-- Expenses -->
              <a href="/departments/${data.id}/expenses" class="btn btn-sm btn-danger" title="Expenses">
                <i class="bx bx-money"></i>
              </a>

              <!-- Sales -->
              <a href="/departments/${data.id}/sales" class="btn btn-sm btn-success" title="Sales">
                <i class="bx bx-shopping-bag"></i>
              </a>

              <!-- Invoices -->
              <a href="/departments/${data.id}/invoices" class="btn btn-sm btn-info text-white" title="Invoices">
                <i class="bx bx-receipt"></i>
              </a>

              <!-- Customers -->
              <a href="/departments/${data.id}/customers" class="btn btn-sm btn-indigo text-white" title="Customers">
                <i class="bx bx-user"></i>
              </a>

              <!-- Stock Management -->
              <a href="/departments/${data.id}/stock" class="btn btn-sm btn-dark text-white" title="Stock">
                <i class="bx bx-package"></i>
              </a>

              <!-- Delete -->
              <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${data.id}" title="Delete">
                <i class="bx bx-trash"></i>
              </button>
            </div>
          `;
            }
          }
        ],
        processing: true,
        drawCallback: function() {
          totalSpan.textContent = this.api().rows().count();
        }
      });

      // === SAME CRUD LOGIC AS BEFORE (unchanged) ===
      form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(form);

        try {
          const res = await fetch('{{ route('departments.store') }}', {
            method: 'POST',
            body: formData,
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
          });

          const json = await res.json();

          if (res.ok) {
            Swal.fire('Success!', json.message || 'Saved!', 'success');
            form.reset();
            document.getElementById('department_id').value = '';
            cancelBtn.style.display = 'none';
            table.ajax.reload(null, false);
          } else {
            const err = json.errors ? Object.values(json.errors).flat().join('<br>') : 'Error';
            Swal.fire('Error!', err, 'error');
          }
        } catch (e) {
          Swal.fire('Error!', 'Network error', 'error');
        }
      });

      tableElement.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
          const id = editBtn.dataset.id;
          fetch(`/departments/edit/${id}`)
            .then(r => r.json())
            .then(d => {
              document.getElementById('department_id').value = d.id;
              document.getElementById('key').value = d.key;
              document.getElementById('name').value = d.name;
              document.getElementById('description').value = d.description || '';
              cancelBtn.style.display = 'inline-block';
              window.scrollTo({
                top: 0,
                behavior: 'smooth'
              });
            });
          return;
        }

        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
          const id = deleteBtn.dataset.id;
          Swal.fire({
            title: 'Delete Department?',
            text: 'All data will be permanently deleted!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete!'
          }).then(result => {
            if (result.isConfirmed) {
              fetch(`/departments/delete/${id}`, {
                method: 'DELETE',
                headers: {
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
              }).then(() => {
                Swal.fire('Deleted!', '', 'success');
                table.ajax.reload();
              });
            }
          });
        }
      });

      cancelBtn.onclick = () => {
        form.reset();
        document.getElementById('department_id').value = '';
        cancelBtn.style.display = 'none';
      };
    });
  </script>
@endsection
