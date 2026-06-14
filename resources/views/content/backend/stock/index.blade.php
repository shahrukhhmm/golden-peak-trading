@extends('layouts/contentNavbarLayout')
@section('title', 'Stock - ' . $department->name)

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
      <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
          <h5 class="mb-0">Stock Management - {{ $department->name }}</h5>
          
          <div class="d-flex align-items-center gap-3">
              <div class="input-group input-group-merge" style="max-width: 420px;">
                  <input type="date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                  <span class="input-group-text">to</span>
                  <input type="date" id="end_date" class="form-control" value="{{ request('end_date') }}">
              </div>
              
              <!-- <button class="btn btn-primary" id="btnFilter">
                  <i class="bx bx-filter-alt"></i> Filter
              </button> -->
              
              
              <a href="#" class="btn btn-success" id="btnDownloadPdf">
                  <i class="bx bx-download"></i> Download PDF
              </a>
              
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                  <i class="bx bx-plus me-1"></i> Add New Item
              </button>
          </div>
      </div>
  </div>

  <div class="card">
    <div class="card-datatable table-responsive">
      <table class="table table-hover" id="stockTable">
        <thead class="table-light">
          <tr>
            <th>SKU</th>
            <th>Name</th>
            <th>Unit</th>
            <th>Stock</th>
            <th>Avg Cost</th>
            <th>Value</th>
            <th width="150">Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  @include('content.backend.stock.modals.add')
  @include('content.backend.stock.modals.add_stock')
  @include('content.backend.stock.modals.issue_stock')
<script>
  document.addEventListener('DOMContentLoaded', function () {

      // =============================================
      // 1. Initialize DataTable
      // =============================================
      const table = new DataTable('#stockTable', {
          ajax: {
              url: '{{ route('departments.stock.data', $department) }}',
              dataSrc: 'data'
          },
          columns: [
              { data: 'sku' },
              {
                  data: 'name',
                  render: function(data) {
                      return data; // already contains <strong> from backend
                  }
              },
              { data: 'unit' },
              {
                  data: 'quantity',
                  render: function(data) {
                      return data; // already contains badge HTML
                  }
              },
              { data: 'avg_cost' },
              { data: 'value' },
              {
                  data: 'actions',
                  orderable: false,
                  searchable: false
              }
          ],
          order: [[1, 'asc']],           // sort by name by default
          pageLength: 25,
          responsive: true,
          processing: true,
          serverSide: false,
          language: {
              processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
          }
      });


      // =============================================
      // 2. Add New Item (Create Stock Item)
      // =============================================
      $('#addForm').on('submit', function(e) {
          e.preventDefault();

          $.ajax({
              url: '{{ route('departments.stock.store', $department) }}',
              method: 'POST',
              data: $(this).serialize(),
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: function(response) {
                  $('#addModal').modal('hide');
                  $('#addForm')[0].reset();
                  table.ajax.reload(null, false);
                  Swal.fire({
                      icon: 'success',
                      title: 'Success!',
                      text: response.message || 'Item created successfully',
                      timer: 2000
                  });
              },
              error: function(xhr) {
                  let msg = 'Something went wrong';
                  if (xhr.responseJSON && xhr.responseJSON.message) {
                      msg = xhr.responseJSON.message;
                  } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                      msg = Object.values(xhr.responseJSON.errors)[0][0];
                  }
                  Swal.fire('Error!', msg, 'error');
              }
          });
      });


      // =============================================
      // 3. Open Add Stock Modal
      // =============================================
      $(document).on('click', '.btn-add-stock', function() {
          const itemId = $(this).data('id');
          const itemName = $(this).data('name');

          $('#addStockItemId').val(itemId);
          $('#addStockModalLabel').text('Add Stock – ' + itemName);
          $('#addStockForm')[0].reset();
          $('#addStockModal').modal('show');
      });


      // =============================================
      // 4. Submit Add Stock (Inward)
      // =============================================
      $('#addStockForm').on('submit', function(e) {
          e.preventDefault();

          const itemId = $('#addStockItemId').val();
          const url = '{{ route('departments.stock.in.store', [$department, ":id"]) }}'
              .replace(':id', itemId);

          $.ajax({
              url: url,
              method: 'POST',
              data: $(this).serialize(),
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: function() {
                  $('#addStockModal').modal('hide');
                  table.ajax.reload(null, false);
                  Swal.fire('Success!', 'Stock added successfully', 'success');
              },
              error: function(xhr) {
                  Swal.fire(
                      'Error!',
                      xhr.responseJSON?.message || 'Failed to add stock',
                      'error'
                  );
              }
          });
      });


      // =============================================
      // 5. Open Issue Stock Modal
      // =============================================
      $(document).on('click', '.btn-issue-stock', function() {
          const id = $(this).data('id');
          const name = $(this).data('name');
          const qty = parseFloat($(this).data('quantity')) || 0;
          const unit = $(this).closest('tr').find('td:eq(2)').text().trim() || 'pcs';

          $('#issueStockItemId').val(id);
          $('#issueStockModalLabel').html('<i class="bx bx-minus-circle"></i> Issue Stock – ' + name);
          $('#issueItemName').text(name);
          $('#issueCurrentQty').text(qty.toFixed(3) + ' ' + unit);
          $('#issueStockForm')[0].reset();
          $('#issueStockModal').modal('show');
      });


      // =============================================
      // 6. Submit Issue Stock (Outward)
      // =============================================
      $('#issueStockForm').on('submit', function(e) {
          e.preventDefault();

          const qtyInput = $(this).find('input[name="quantity"]');
          const currentQtyText = $('#issueCurrentQty').text();
          const maxQty = parseFloat(currentQtyText) || 0;

          const requestedQty = parseFloat(qtyInput.val()) || 0;

          if (requestedQty > maxQty) {
              Swal.fire('Error!', 'Cannot issue more than available stock!', 'error');
              return;
          }

          if (requestedQty <= 0) {
              Swal.fire('Error!', 'Please enter a valid quantity', 'error');
              return;
          }

          const itemId = $('#issueStockItemId').val();
          const url = '{{ route('departments.stock.out.store', [$department, ":id"]) }}'
              .replace(':id', itemId);

          $.ajax({
              url: url,
              method: 'POST',
              data: $(this).serialize(),
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: function() {
                  $('#issueStockModal').modal('hide');
                  table.ajax.reload(null, false);
                  Swal.fire('Success!', 'Stock issued successfully', 'success');
              },
              error: function(xhr) {
                  Swal.fire(
                      'Error!',
                      xhr.responseJSON?.message || 'Failed to issue stock',
                      'error'
                  );
              }
          });
      });


      // =============================================
      // 7. Date Filter Button
      // =============================================
      document.getElementById('btnFilter')?.addEventListener('click', function() {
          const start = document.getElementById('start_date').value;
          const end = document.getElementById('end_date').value;

          if (start && end && start > end) {
              Swal.fire('Invalid Range', 'End date must be after start date', 'error');
              return;
          }

          let url = '{{ route('departments.stock.data', $department) }}';

          const params = new URLSearchParams();
          if (start) params.append('start_date', start);
          if (end) params.append('end_date', end);

          if (params.toString()) {
              url += '?' + params.toString();
          }

          table.ajax.url(url).load();
      });


      // =============================================
      // 8. Download PDF Button
      // =============================================
      document.getElementById('btnDownloadPdf')?.addEventListener('click', function(e) {
          e.preventDefault();

          const start = document.getElementById('start_date').value;
          const end = document.getElementById('end_date').value;

          let url = '{{ route("departments.stock.pdf", $department) }}';

          const params = new URLSearchParams();
          if (start) params.append('start_date', start);
          if (end) params.append('end_date', end);

          if (params.toString()) {
              url += '?' + params.toString();
          }

          window.open(url, '_blank');
      });

  });
</script>
@endsection
