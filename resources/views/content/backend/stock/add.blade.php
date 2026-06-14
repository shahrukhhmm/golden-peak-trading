@extends('layouts/contentNavbarLayout')
@section('title', 'Add Stock')
@section('content')
  <div class="card">
    <div class="card-header">
      <h5>Add Stock – {{ $stockItem->name }}</h5>
    </div>
    <div class="card-body">
      <form action="{{ route('departments.stock.in', [$department, $stockItem]) }}" method="POST">
        @csrf
        <div class="row g-3">
          <div class="col-md-4"><label>Quantity</label><input type="number" step="0.001" name="quantity"
              class="form-control" required></div>
          <div class="col-md-4"><label>Unit Price</label><input type="number" step="0.01" name="unit_price"
              class="form-control" required></div>
          <div class="col-md-4"><label>Date</label><input type="date" name="moved_at" class="form-control"
              value="{{ today()->format('Y-m-d') }}" required></div>
        </div>
        <div class="mt-3">
          <textarea name="note" class="form-control" placeholder="Note (optional)" rows="2"></textarea>
        </div>
        <div class="mt-3">
          <button class="btn btn-success">Add Stock</button>
          <a href="{{ route('departments.stock.index', $department) }}" class="btn btn-secondary">Back</a>
        </div>
      </form>
    </div>
  </div>
@endsection
