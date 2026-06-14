<div class="modal fade" id="addModal">
  <div class="modal-dialog">
    <form id="addForm" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5>Add Stock Item</h5>
      </div>
      <div class="modal-body">
        <input type="text" name="name" class="form-control mb-3" placeholder="Name" required>
        <input type="text" name="sku" class="form-control mb-3" placeholder="SKU (optional)">
        <input type="text" name="unit" class="form-control" value="pcs" placeholder="Unit">
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>
