<div class="d-flex gap-1">
  <button type="button" class="btn btn-icon btn-outline-success btn-add-stock" data-id="{{ $i->id }}"
    data-name="{{ $i->name }}" title="Add Stock">
    <i class="bx bx-plus"></i>
  </button>

  <a href="{{ route('departments.stock.history', [$department, $i]) }}" class="btn btn-icon btn-outline-info"
    title="View History">
    <i class="bx bx-history"></i>
  </a>

  <button type="button" class="btn btn-icon btn-outline-warning btn-issue-stock" data-id="{{ $i->id }}"
    data-name="{{ e($i->name) }}" data-quantity="{{ $i->quantity }}" title="Issue Stock (OUT)"
    {{ $i->quantity <= 0 ? 'disabled' : '' }}>
    <i class="bx bx-minus"></i>
  </button>

  <button type="button" class="btn btn-icon btn-outline-danger" title="Delete (soon)">
    <i class="bx bx-trash"></i>
  </button>
</div>
