<div class="modal fade" id="addStockModal">
  <div class="modal-dialog">
    <form id="addStockForm" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="addStockModalLabel">Add Stock</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="addStockItemId" name="item_id">

        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Quantity <span class="text-danger">*</span></label>
            <input type="number" step="0.001" name="quantity" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Unit Price (Rs.) <span class="text-danger">*</span></label>
            <input type="number" step="0.01" name="unit_price" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Date <span class="text-danger">*</span></label>
            <input type="date" name="moved_at" class="form-control" value="{{ today()->format('Y-m-d') }}" required>
          </div>
        </div>
        <div class="mt-3">
          <label class="form-label">Note (optional)</label>
          <textarea name="note" class="form-control" rows="2" placeholder="Purchase reference, supplier, etc."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-success">Add to Stock</button>
      </div>
    </form>
  </div>
</div>
