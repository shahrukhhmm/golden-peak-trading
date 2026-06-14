@extends('layouts/contentNavbarLayout')

@section('title', 'Sales - ' . $department->name)

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
      <li class="breadcrumb-item active">Sales</li>
    </ol>
  </nav>

  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">
        Record Sale – {{ $department->name }}
        <span class="badge bg-label-success ms-2">PKR</span>
      </h5>
      <a href="{{ route('departments.categories.index', $department) }}" class="btn btn-sm btn-label-secondary">
        <i class="bx bx-arrow-back"></i> Back to Categories
      </a>
    </div>

    <div class="card-body">
      <form id="saleForm">
        @csrf
        <input type="hidden" name="id" id="sale_id">

        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Sale Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control" name="sale_date" id="sale_date" required
              value="{{ now()->format('Y-m-d') }}">
          </div>

          <div class="col-md-8">
            <label class="form-label">Note</label>
            <textarea class="form-control" name="note" id="note" rows="2"
              placeholder="e.g., Monthly salt sales to XYZ company..."></textarea>
          </div>
        </div>

        <div class="mt-4">
          <h6 class="mb-2">Sale Items <small class="text-muted">(Add multiple items)</small></h6>

          <!-- Items Container -->
          <div id="itemsContainer">
            <div class="item-row" data-index="0">
              <div class="row g-2 align-items-end">
                <div class="col-md-5">
                  <label class="form-label">Description <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="items[0][description]" required
                    placeholder="e.g., 50kg Salt Bag">
                </div>
                <div class="col-md-2">
                  <label class="form-label">Quantity <span class="text-danger">*</span></label>
                  <input type="number" step="0.001" min="0.001" class="form-control quantity"
                    name="items[0][quantity]" required value="1" placeholder="1">
                </div>
                <div class="col-md-2">
                  <label class="form-label">Rate (PKR) <span class="text-danger">*</span></label>
                  <input type="number" step="0.01" min="0.01" class="form-control rate" name="items[0][rate]"
                    required placeholder="0.00">
                </div>
                <div class="col-md-2">
                  <label class="form-label">Total</label>
                  <input type="number" step="0.01" class="form-control total bg-light" readonly>
                </div>
                <div class="col-md-1">
                  <button type="button" class="btn btn-outline-danger remove-item mt-4" style="display:none;">
                    <i class="bx bx-trash"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-2">
            <button type="button" id="addItemBtn" class="btn btn-outline-primary">
              <i class="bx bx-plus"></i> Add Item
            </button>
          </div>

          <div class="row mt-3">
            <div class="col-md-8"></div>
            <div class="col-md-4">
              <div class="card">
                <div class="card-body text-end">
                  <h5 class="text-primary" id="grandTotal">Rs. 0.00</h5>
                  <small class="text-muted">Grand Total</small>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-primary">
              <i class="bx bx-save me-1"></i> {{ request()->filled('id') ? 'Update' : 'Save' }} Sale
            </button>
            <button type="button" id="cancelBtn" class="btn btn-label-secondary ms-2" style="display:none;">
              <i class="bx bx-x me-1"></i> Cancel
            </button>
          </div>
      </form>
    </div>
  </div>
  </div>

  <!-- Sales Table -->
  <div class="card">
    <div class="card-header">
      <h5 class="card-title mb-0">All Sales ({{ $department->sales()->count() }})</h5>
    </div>
    <div class="card-datatable table-responsive">
      <table class="table table-hover" id="salesTable" style="width:100%">
        <thead class="table-light">
          <tr>
            <th width="60">#</th>
            <th>Date</th>
            <th class="text-end">Total</th>
            <th>Items</th>
            <th>Note</th>
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
      const form = document.getElementById('saleForm');
      const tableEl = document.getElementById('salesTable');
      const cancelBtn = document.getElementById('cancelBtn');
      const addItemBtn = document.getElementById('addItemBtn');
      const itemsContainer = document.getElementById('itemsContainer');
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      let itemIndex = 1;

      // === DYNAMIC ITEMS MANAGEMENT ===
      function calculateItemTotal(row) {
        const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
        const rate = parseFloat(row.querySelector('.rate').value) || 0;
        const total = quantity * rate;
        row.querySelector('.total').value = total.toFixed(2);
        calculateGrandTotal();
      }

      function calculateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
          const total = parseFloat(row.querySelector('.total').value) || 0;
          grandTotal += total;
        });
        document.getElementById('grandTotal').textContent = 'Rs. ' + grandTotal.toFixed(2);
      }

      // Add item row
      addItemBtn.addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.className = 'item-row mt-3';
        newRow.dataset.index = itemIndex;
        newRow.innerHTML = `
            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">Description <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="items[${itemIndex}][description]" required placeholder="e.g., 25kg Salt Bag">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" step="0.001" min="0.001" class="form-control quantity" name="items[${itemIndex}][quantity]" required value="1" placeholder="1">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Rate (PKR) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0.01" class="form-control rate" name="items[${itemIndex}][rate]" required placeholder="0.00">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Total</label>
                    <input type="number" step="0.01" class="form-control total bg-light" readonly>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-danger remove-item mt-4">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </div>
        `;

        itemsContainer.appendChild(newRow);
        itemIndex++;

        // Add event listeners to new row
        const quantityInput = newRow.querySelector('.quantity');
        const rateInput = newRow.querySelector('.rate');
        const totalInput = newRow.querySelector('.total');
        const removeBtn = newRow.querySelector('.remove-item');

        quantityInput.addEventListener('input', () => calculateItemTotal(newRow));
        rateInput.addEventListener('input', () => calculateItemTotal(newRow));
        removeBtn.addEventListener('click', () => {
          newRow.remove();
          calculateGrandTotal();
        });

        // Focus first input
        quantityInput.focus();
      });

      // Event delegation for existing rows
      itemsContainer.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity') || e.target.classList.contains('rate')) {
          const row = e.target.closest('.item-row');
          calculateItemTotal(row);
        }
      });

      itemsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
          e.target.closest('.item-row').remove();
          calculateGrandTotal();
          if (document.querySelectorAll('.item-row').length === 1) {
            document.querySelector('.remove-item').style.display = 'none';
          }
        }
      });

      // Initialize first row
      document.addEventListener('DOMContentLoaded', function() {
        const firstRow = document.querySelector('.item-row');
        const firstQuantity = firstRow.querySelector('.quantity');
        const firstRate = firstRow.querySelector('.rate');

        firstQuantity.addEventListener('input', () => calculateItemTotal(firstRow));
        firstRate.addEventListener('input', () => calculateItemTotal(firstRow));
      });

      // === DATATABLE ===
      const table = new DataTable(tableEl, {
        ajax: {
          url: '{{ route('departments.sales.data', $department) }}',
          dataSrc: 'data',
          error: function(xhr, error, thrown) {
            Swal.fire('Error!', 'Failed to load sales data', 'error');
          }
        },
        columns: [{
            data: 'id',
            width: '60px',
            className: 'text-center'
          },
          {
            data: 'sale_date'
          },
          {
            data: 'total',
            className: 'text-end fw-semibold',
            width: '120px'
          },
          {
            data: 'items_preview',
            render: function(data, type, row) {
              return `
                        <div>
                            <small class="text-muted">${row.items_count} items:</small><br>
                            <small>${data}</small>
                        </div>
                    `;
            },
            width: '200px'
          },
          {
            data: 'note',
            width: '150px'
          },
          {
            data: 'creator',
            width: '100px',
            render: data => `<small class="text-muted">${data}</small>`
          },
          {
            data: 'action',
            orderable: false,
            searchable: false,
            width: '120px'
          }
        ],
        order: [
          [1, 'desc']
        ],
        processing: true,
        language: {
          processing: '<div class="spinner-border text-primary"></div>',
          emptyTable: 'No sales recorded yet'
        }
      });

      // === FORM SUBMIT ===
      form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Validate at least one item
        const items = document.querySelectorAll('.item-row input[name$="[description]"]');
        if (items.length === 0 || !items[0].value.trim()) {
          Swal.fire('Error!', 'Please add at least one item', 'error');
          return;
        }

        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Saving...';
        submitBtn.disabled = true;

        const formData = new FormData(form);

        try {
          const response = await fetch('{{ route('departments.sales.store', $department) }}', {
            method: 'POST',
            body: formData,
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            }
          });

          const result = await response.json();

          if (response.ok && result.success) {
            Swal.fire('Success!', result.message, 'success');
            form.reset();
            document.getElementById('sale_id').value = '';
            cancelBtn.style.display = 'none';
            // Reset form
            document.getElementById('sale_date').value = '{{ now()->format('Y-m-d') }}';
            document.getElementById('note').value = '';
            document.getElementById('grandTotal').textContent = 'Rs. 0.00';
            // Reset items to one empty row
            itemsContainer.innerHTML = `
                    <div class="item-row" data-index="0">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="items[0][description]" required placeholder="e.g., 50kg Salt Bag">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input type="number" step="0.001" min="0.001" class="form-control quantity" name="items[0][quantity]" required value="1" placeholder="1">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Rate (PKR) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0.01" class="form-control rate" name="items[0][rate]" required placeholder="0.00">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Total</label>
                                <input type="number" step="0.01" class="form-control total bg-light" readonly>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-outline-danger remove-item mt-4" style="display:none;">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            itemIndex = 1;
            // Re-attach event listeners
            const firstRow = document.querySelector('.item-row');
            const firstQuantity = firstRow.querySelector('.quantity');
            const firstRate = firstRow.querySelector('.rate');
            firstQuantity.addEventListener('input', () => calculateItemTotal(firstRow));
            firstRate.addEventListener('input', () => calculateItemTotal(firstRow));
            table.ajax.reload(null, false);
          } else {
            let errorMessage = result.message || 'Something went wrong';
            if (result.errors) {
              errorMessage = Object.values(result.errors).flat().join('<br>');
            }
            Swal.fire('Error!', errorMessage, 'error');
          }
        } catch (error) {
          Swal.fire('Error!', 'Network error. Please try again.', 'error');
        } finally {
          submitBtn.innerHTML = originalText;
          submitBtn.disabled = false;
        }
      });

      // === EDIT BUTTON ===
      tableEl.addEventListener('click', async function(e) {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
          const id = editBtn.dataset.id;

          try {
            const response = await fetch(`/departments/{{ $department->id }}/sales/${id}/edit`);
            const data = await response.json();

            if (response.ok) {
              // Populate form
              document.getElementById('sale_id').value = data.id;
              document.getElementById('sale_date').value = data.sale_date;
              document.getElementById('note').value = data.note || '';

              // Clear existing items
              itemsContainer.innerHTML = '';
              itemIndex = 0;

              // Add items
              data.items.forEach(item => {
                const newRow = document.createElement('div');
                newRow.className = 'item-row mt-3';
                newRow.dataset.index = itemIndex;
                newRow.innerHTML = `
                            <div class="row g-2 align-items-end">
                                <div class="col-md-5">
                                    <label class="form-label">Description <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="items[${itemIndex}][description]" value="${item.description}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                    <input type="number" step="0.001" min="0.001" class="form-control quantity" name="items[${itemIndex}][quantity]" value="${item.quantity}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Rate (PKR) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0.01" class="form-control rate" name="items[${itemIndex}][rate]" value="${item.rate}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Total</label>
                                    <input type="number" step="0.01" class="form-control total bg-light" value="${(item.quantity * item.rate).toFixed(2)}" readonly>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-outline-danger remove-item mt-4">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `;

                itemsContainer.appendChild(newRow);
                itemIndex++;

                // Add event listeners
                const row = newRow;
                const quantityInput = row.querySelector('.quantity');
                const rateInput = row.querySelector('.rate');
                const removeBtn = row.querySelector('.remove-item');

                quantityInput.addEventListener('input', () => calculateItemTotal(row));
                rateInput.addEventListener('input', () => calculateItemTotal(row));
                removeBtn.addEventListener('click', () => {
                  row.remove();
                  calculateGrandTotal();
                });
              });

              // Calculate totals
              calculateGrandTotal();

              // Show cancel button
              cancelBtn.style.display = 'inline-block';
              document.querySelector('button[type="submit"]').textContent = 'Update Sale';

              window.scrollTo({
                top: 0,
                behavior: 'smooth'
              });
            }
          } catch (error) {
            Swal.fire('Error!', 'Failed to load sale data', 'error');
          }
        }

        // === DELETE BUTTON ===
        const delBtn = e.target.closest('.delete-btn');
        if (delBtn) {
          const id = delBtn.dataset.id;

          Swal.fire({
            title: 'Delete Sale?',
            text: 'This will delete all items in this sale!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete!'
          }).then(result => {
            if (result.isConfirmed) {
              fetch(`/departments/{{ $department->id }}/sales/${id}`, {
                  method: 'DELETE',
                  headers: {
                    'X-CSRF-TOKEN': csrfToken
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
                });
            }
          });
        }
      });

      // === CANCEL BUTTON ===
      cancelBtn.addEventListener('click', function() {
        form.reset();
        document.getElementById('sale_id').value = '';
        document.getElementById('sale_date').value = '{{ now()->format('Y-m-d') }}';
        document.getElementById('note').value = '';
        document.getElementById('grandTotal').textContent = 'Rs. 0.00';
        document.querySelector('button[type="submit"]').textContent = 'Save Sale';
        this.style.display = 'none';

        // Reset items
        itemsContainer.innerHTML = `
            <div class="item-row" data-index="0">
                <div class="row g-2 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="items[0][description]" required placeholder="e.g., 50kg Salt Bag">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" step="0.001" min="0.001" class="form-control quantity" name="items[0][quantity]" required value="1" placeholder="1">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Rate (PKR) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0.01" class="form-control rate" name="items[0][rate]" required placeholder="0.00">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Total</label>
                        <input type="number" step="0.01" class="form-control total bg-light" readonly>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-outline-danger remove-item mt-4" style="display:none;">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        itemIndex = 1;

        // Re-attach first row listeners
        const firstRow = document.querySelector('.item-row');
        const firstQuantity = firstRow.querySelector('.quantity');
        const firstRate = firstRow.querySelector('.rate');
        firstQuantity.addEventListener('input', () => calculateItemTotal(firstRow));
        firstRate.addEventListener('input', () => calculateItemTotal(firstRow));
      });

      // Set default date
      document.getElementById('sale_date').value = '{{ now()->format('Y-m-d') }}';
    });
  </script>
@endsection
