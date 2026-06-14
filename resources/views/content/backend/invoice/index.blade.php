{{-- resources/views/content/backend/invoice/index.blade.php --}}
@extends('layouts/contentNavbarLayout')

@section('title', 'Invoices - ' . $department->name)

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
      <li class="breadcrumb-item active">Invoices</li>
    </ol>
  </nav>

  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">
        Create Invoice – {{ $department->name }}
        <span class="badge bg-label-success ms-2">PKR</span>
      </h5>
      <a href="{{ route('departments.customers.index', $department) }}" class="btn btn-sm btn-label-secondary">
        Manage Customers
      </a>
    </div>

    <div class="card-body">
      <form id="invoiceForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" id="invoice_id">

        <div class="row g-3 mb-3">
          <div class="col-md-4">
            <label class="form-label">Customer</label>
            <select class="form-select" name="customer_id" id="customer_id">
              <option value="">Walk-in Customer</option>
              @foreach ($customers as $c)
                <option value="{{ $c->id }}">{{ $c->name }} {{ $c->code ? "({$c->code})" : '' }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Invoice Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control" name="invoice_date" id="invoice_date" required
              value="{{ now()->format('Y-m-d') }}">
          </div>
          <div class="col-md-3">
            <label class="form-label">Tax Amount (PKR)</label>
            <input type="number" step="0.01" min="0" class="form-control" name="tax" id="tax_input"
              value="0">
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-12">
            <label class="form-label">Description (Optional)</label>
            <textarea class="form-control" name="description" id="description" rows="2"
              placeholder="e.g., Monthly salt supply invoice"></textarea>
          </div>
        </div>

        <h6 class="mb-3">Invoice Items</h6>
        <div id="itemsContainer">
          <div class="item-row row g-3 align-items-end mb-2 border-bottom pb-2">
            <div class="col-md-6">
              <input type="text" class="form-control" name="items[0][description]" placeholder="Item description"
                required>
            </div>
            <div class="col-md-2">
              <input type="number" step="0.001" min="0.001" class="form-control quantity" name="items[0][quantity]"
                value="1" required>
            </div>
            <div class="col-md-2">
              <input type="number" step="0.01" min="0" class="form-control rate" name="items[0][rate]" required
                placeholder="0.00">
            </div>
            <div class="col-md-2">
              <input type="number" class="form-control total bg-light" readonly value="0.00">
            </div>
          </div>
        </div>

        <button type="button" id="addItemBtn" class="btn btn-sm btn-outline-primary mb-3">
          Add Another Item
        </button>

        <div class="row mt-4">
          <div class="col-md-8"></div>
          <div class="col-md-4">
            <div class="bg-light p-3 rounded">
              <div class="d="d-flex justify-content-between mb-2">
                <strong>Subtotal:</strong>
                <span id="subtotal">0.00</span> PKR
              </div>
              <div class="d-flex justify-content-between mb-2">
                <strong>Tax:</strong>
                <span id="tax_display">0.00</span> PKR
              </div>
              <hr>
              <div class="d-flex justify-content-between">
                <h5>Total:</h5>
                <h5 class="text-primary" id="grandTotal">0.00 PKR</h5>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-4">
          <label class="form-label">Attachments (PDF, Images) - Optional</label>
          <input type="file" name="attachments[]" multiple class="form-control" accept=".pdf,.jpg,.jpeg,.png">
          <small class="text-muted">You can attach receipts, contracts, etc.</small>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary me-2">
            <i class="bx bx bx-save"></i> Save Invoice
          </button>
          <button type="button" id="cancelBtn" class="btn btn-label-secondary" style="display:none;">
            Cancel Edit
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Invoices List -->
  <div class="card">
    <div class="card-header">
      <h5 class="mb-0">All Invoices</h5>
    </div>
    <div class="card-datatable table-responsive">
      <table class="table table-hover" id="invoicesTable" style="width:100%">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Invoice No</th>
            <th>Customer</th>
            <th>Date</th>
            <th class="text-end">Total</th>
            <th>Items</th>
            <th>By</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('invoiceForm');
      const tableEl = document.getElementById('invoicesTable');
      const itemsContainer = document.getElementById('itemsContainer');
      const addItemBtn = document.getElementById('addItemBtn');
      const cancelBtn = document.getElementById('cancelBtn');
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      let itemIndex = 1;

      // Calculate single row total
      function calculateRowTotal(row) {
        const qty = parseFloat(row.querySelector('.quantity').value) || 0;
        const rate = parseFloat(row.querySelector('.rate').value) || 0;
        const total = qty * rate;
        row.querySelector('.total').value = total.toFixed(2);
        calculateGrandTotal();
      }

      // Calculate subtotal, tax, grand total
      function calculateGrandTotal() {
        let subtotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
          subtotal += parseFloat(row.querySelector('.total').value) || 0;
        });

        const tax = parseFloat(document.getElementById('tax_input').value) || 0;

        document.getElementById('subtotal').textContent = subtotal.toFixed(2);
        document.getElementById('tax_display').textContent = tax.toFixed(2);
        document.getElementById('grandTotal').textContent = (subtotal + tax).toFixed(2) + ' PKR';
      }

      // Add new item row
      addItemBtn.addEventListener('click', function() {
        const row = document.createElement('div');
        row.className = 'item-row row g-3 align-items-end mb-2 border-bottom pb-2';
        row.innerHTML = `
            <div class="col-md-6">
                <input type="text" class="form-control" name="items[${itemIndex}][description]" placeholder="Item description" required>
            </div>
            <div class="col-md-2">
                <input type="number" step="0.001" min="0.001" class="form-control quantity" name="items[${itemIndex}][quantity]" value="1" required>
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" min="0" class="form-control rate" name="items[${itemIndex}][rate]" required>
            </div>
            <div class="col-md-1">
                <input type="number" class="form-control total bg-light" readonly value="0.00">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-sm btn-outline-danger remove-item">
                    Remove
                </button>
            </div>
        `;

        itemsContainer.appendChild(row);
        itemIndex++;

        // Attach events
        row.querySelector('.quantity').addEventListener('input', () => calculateRowTotal(row));
        row.querySelector('.rate').addEventListener('input', () => calculateRowTotal(row));
        row.querySelector('.remove-item').addEventListener('click', () => {
          row.remove();
          calculateGrandTotal();
        });
      });

      // Event delegation for dynamic rows
      itemsContainer.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity') || e.target.classList.contains('rate')) {
          calculateRowTotal(e.target.closest('.item-row'));
        }
      });

      itemsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
          e.target.closest('.item-row').remove();
          calculateGrandTotal();
        }
      });

      document.getElementById('tax_input').addEventListener('input', calculateGrandTotal);

      // Initialize first row events
      const firstRow = itemsContainer.querySelector('.item-row');
      firstRow.querySelector('.quantity').addEventListener('input', () => calculateRowTotal(firstRow));
      firstRow.querySelector('.rate').addEventListener('input', () => calculateRowTotal(firstRow));

      // DataTable
      const table = new DataTable(tableEl, {
        ajax: {
          url: '{{ route('departments.invoices.data', $department) }}',
          dataSrc: 'data'
        },
        columns: [{
            data: 'id',
            width: '60px'
          },
          {
            data: 'invoice_number'
          },
          {
            data: 'customer'
          },
          {
            data: 'invoice_date'
          },
          {
            data: 'total',
            className: 'text-end fw-bold'
          },
          {
            data: 'items_count',
            className: 'text-center'
          },
          {
            data: 'creator'
          },
          {
            data: 'action',
            orderable: false,
            searchable: false,
            width: '180px'
          }
        ],
        order: [
          [0, 'desc']
        ],
        processing: true,
        language: {
          processing: '<div class="spinner-border text-primary"></div>'
        }
      });

      // Form Submit
      form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = form.querySelector('button[type="submit"]');
        const original = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Saving...';

        const formData = new FormData(form);

        try {
          const res = await fetch('{{ route('departments.invoices.store', $department) }}', {
            method: 'POST',
            body: formData,
            headers: {
              'X-CSRF-TOKEN': csrfToken
            }
          });

          const json = await res.json();

          if (res.ok && json.success) {
            Swal.fire('Success!', json.message || 'Invoice saved!', 'success');
            form.reset();
            document.getElementById('invoice_id').value = '';
            document.getElementById('invoice_date').value = '{{ now()->format('Y-m-d') }}';
            cancelBtn.style.display = 'none';
            itemsContainer.innerHTML = `
                    <div class="item-row row g-3 align-items-end mb-2 border-bottom pb-2">
                        <div class="col-md-6"><input type="text" class="form-control" name="items[0][description]" placeholder="Item description" required></div>
                        <div class="col-md-2"><input type="number" step="0.001" min="0.001" class="form-control quantity" name="items[0][quantity]" value="1" required></div>
                        <div class="col-md-2"><input type="number" step="0.01" class="form-control rate" name="items[0][rate]" required></div>
                        <div class="col-md-2"><input type="number" class="form-control total bg-light" readonly value="0.00"></div>
                    </div>
                `;
            itemIndex = 1;
            calculateGrandTotal();
            table.ajax.reload();
          } else {
            const msg = json.errors ? Object.values(json.errors).flat().join('<br>') : (json.message ||
              'Error');
            Swal.fire('Error!', msg, 'error');
          }
        } catch (err) {
          Swal.fire('Error!', 'Network error', 'error');
        } finally {
          submitBtn.disabled = false;
          submitBtn.innerHTML = original;
        }
      });

      // Edit Invoice
      tableEl.addEventListener('click', async function(e) {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
          const id = editBtn.dataset.id;
          try {
            const res = await fetch(`{{ url('departments') }}/{{ $department->id }}/invoices/${id}/data`);
            const inv = await res.json();

            // Fill form
            document.getElementById('invoice_id').value = inv.id;
            document.getElementById('customer_id').value = inv.customer_id || '';
            document.getElementById('invoice_date').value = inv.invoice_date;
            document.getElementById('description').value = inv.description || '';
            document.getElementById('tax_input').value = inv.tax;

            // Clear items
            itemsContainer.innerHTML = '';
            itemIndex = 0;

            inv.items.forEach(item => {
              const row = document.createElement('div');
              row.className = 'item-row row g-3 align-items-end mb-2 border-bottom pb-2';
              row.innerHTML = `
                        <div class="col-md-6"><input type="text" class="form-control" name="items[${itemIndex}][description]" value="${item.description}" required></div>
                        <div class="col-md-2"><input type="number" step="0.001" class="form-control quantity" name="items[${itemIndex}][quantity]" value="${item.quantity}" required></div>
                        <div class="col-md-2"><input type="number" step="0.01" class="form-control rate" name="items[${itemIndex}][rate]" value="${item.rate}" required></div>
                        <div class="col-md-1"><input type="number" class="form-control total bg-light" value="${item.total}" readonly></div>
                        <div class="col-md-1"><button type="button" class="btn btn-sm btn-outline-danger remove-item">Remove</button></div>
                    `;
              itemsContainer.appendChild(row);
              itemIndex++;
            });

            calculateGrandTotal();
            cancelBtn.style.display = 'inline-block';
            window.scrollTo({
              top: 0,
              behavior: 'smooth'
            });

          } catch (err) {
            Swal.fire('Error', 'Could not load invoice', 'error');
          }
        }

        // Delete
        const delBtn = e.target.closest('.delete-btn');
        if (delBtn) {
          const id = delBtn.dataset.id;
          Swal.fire({
            title: 'Delete invoice?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete!'
          }).then(result => {
            if (result.isConfirmed) {
              fetch(`{{ url('departments') }}/{{ $department->id }}/invoices/${id}`, {
                method: 'DELETE',
                headers: {
                  'X-CSRF-TOKEN': csrfToken
                }
              }).then(() => {
                Swal.fire('Deleted!', '', 'success');
                table.ajax.reload();
              });
            }
          });
        }
      });

      // Cancel Edit
      cancelBtn.addEventListener('click', function() {
        form.reset();
        document.getElementById('invoice_id').value = '';
        document.getElementById('invoice_date').value = '{{ now()->format('Y-m-d') }}';
        this.style.display = 'none';
        itemsContainer.innerHTML = `
            <div class="item-row row g-3 align-items-end mb-2 border-bottom pb-2">
                <div class="col-md-6"><input type="text" class="form-control" name="items[0][description]" placeholder="Item description" required></div>
                <div class="col-md-2"><input type="number" step="0.001" min="0.001" class="form-control quantity" name="items[0][quantity]" value="1" required></div>
                <div class="col-md-2"><input type="number" step="0.01" class="form-control rate" name="items[0][rate]" required></div>
                <div class="col-md-2"><input type="number" class="form-control total bg-light" readonly value="0.00"></div>
            </div>
        `;
        itemIndex = 1;
        calculateGrandTotal();
      });

      // Init
      calculateGrandTotal();
    });
  </script>
@endsection
