<div class="modal fade" id="issueStockModal">
  <div class="modal-dialog ">
    <form id="issueStockForm" class="modal-content">
      @csrf
      <div class="modal-header ">
        <h5 class="modal-title" id="issueStockModalLabel">
          <i class="bx bx-minus-circle"></i> Issue Stock
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="issueStockItemId" name="item_id">

        <div class="alert alert-warning">
          <strong id="issueItemName"></strong><br>
          Current Stock: <strong id="issueCurrentQty">0</strong>
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Quantity to Issue <span class="text-danger">*</span></label>
            <input type="number" step="0.001" name="quantity" class="form-control" min="0.001" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Date <span class="text-danger">*</span></label>
            <input type="date" name="moved_at" class="form-control" value="{{ today()->format('Y-m-d') }}" required>
          </div>
        </div>

        <div class="mt-3">
          <label class="form-label">Note (optional)</label>
          <textarea name="note" class="form-control" rows="3"
            placeholder="Issued to production / customer / wastage etc."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-warning">
          <i class="bx bx-check"></i> Issue Stock
        </button>
      </div>
    </form>
  </div>
</div>
