@extends('layouts/contentNavbarLayout')

@section('title', 'Customers - ' . $department->name)

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
    <div class="card-header">
      <h5 class="mb-0">Manage Customers – {{ $department->name }}</h5>
      <small class="text-muted">Global customers are visible to all departments</small>
    </div>
    <div class="card-body">
      <form id="customerForm">
        @csrf
        <input type="hidden" name="id" id="customer_id">

        <div class="row g-3">
          <div class="col-md-5">
            <label>Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="name" id="name" required>
          </div>
          <div class="col-md-3">
            <label>Code (Optional)</label>
            <input type="text" class="form-control" name="code" id="code" placeholder="CUST-001">
          </div>
          <div class="col-md-4">
            <label>Phone</label>
            <input type="text" class="form-control" name="phone" id="phone">
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-8">
            <label>Address</label>
            <input type="text" class="form-control" name="address" id="address">
          </div>
          <div class="col-md-4">
            <label>Notes</label>
            <textarea class="form-control" name="notes" id="notes" rows="1"></textarea>
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">Save Customer</button>
          <button type="button" id="cancelBtn" class="btn btn-label-secondary ms-2" style="display:none;">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h5 class="mb-0">All Customers</h5>
    </div>
    <div class="card-datatable table-responsive">
      <table class="table table-hover" id="customersTable" style="width:100%">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Type</th>
            <th>Actions</th>
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
      const form = document.getElementById('customerForm');
      const tableEl = document.getElementById('customersTable');
      const cancelBtn = document.getElementById('cancelBtn');
      const csrf = document.querySelector('meta[name="csrf-token"]').content;

      const table = new DataTable(tableEl, {
        ajax: {
          url: '{{ route('departments.customers.data', $department) }}',
          dataSrc: 'data'
        },
        columns: [{
            data: 'id',
            width: '60px'
          },
          {
            data: 'name',
            render: data => data
          },
          {
            data: 'phone'
          },
          {
            data: 'address'
          },
          {
            data: 'type',
            className: 'text-center'
          },
          {
            data: 'action',
            orderable: false,
            searchable: false,
            width: '120px'
          }
        ],
        order: [
          [1, 'asc']
        ],
        processing: true
      });

      // Save
      form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const fd = new FormData(form);

        try {
          const res = await fetch('{{ route('departments.customers.store', $department) }}', {
            method: 'POST',
            body: fd,
            headers: {
              'X-CSRF-TOKEN': csrf
            }
          });

          const json = await res.json();

          if (res.ok) {
            Swal.fire('Success!', json.message, 'success');
            form.reset();
            document.getElementById('customer_id').value = '';
            cancelBtn.style.display = 'none';
            table.ajax.reload();
          } else {
            const msg = json.errors ? Object.values(json.errors).flat().join('<br>') : json.message;
            Swal.fire('Error!', msg, 'error');
          }
        } catch (e) {
          Swal.fire('Error!', 'Network error', 'error');
        }
      });

      // Edit
      tableEl.addEventListener('click', async function(e) {
        const edit = e.target.closest('.edit-btn');
        if (edit) {
          const id = edit.dataset.id;
          const res = await fetch(`/departments/{{ $department->id }}/customers/${id}/edit`);
          const c = await res.json();

          document.getElementById('customer_id').value = c.id;
          document.getElementById('name').value = c.name;
          document.getElementById('code').value = c.code || '';
          document.getElementById('phone').value = c.phone || '';
          document.getElementById('address').value = c.address || '';
          document.getElementById('notes').value = c.notes || '';
          cancelBtn.style.display = 'inline-block';
          window.scrollTo({
            top: 0,
            behavior: 'smooth'
          });
        }

        // Delete
        const del = e.target.closest('.delete-btn');
        if (del) {
          const id = del.dataset.id;
          Swal.fire({
            title: 'Delete customer?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes'
          }).then(r => {
            if (r.isConfirmed) {
              fetch(`/departments/{{ $department->id }}/customers/${id}`, {
                method: 'DELETE',
                headers: {
                  'X-CSRF-TOKEN': csrf
                }
              }).then(() => {
                Swal.fire('Deleted!', '', 'success');
                table.ajax.reload();
              });
            }
          });
        }
      });

      // Cancel
      cancelBtn.onclick = () => {
        form.reset();
        document.getElementById('customer_id').value = '';
        cancelBtn.style.display = 'none';
      };
    });
  </script>
@endsection
