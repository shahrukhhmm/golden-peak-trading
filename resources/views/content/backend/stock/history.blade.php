@extends('layouts/contentNavbarLayout')

@section('title', 'Stock History - ' . $stockItem->name)

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-1">
                <i class="bx bx-history me-2"></i>
                Stock History: <strong>{{ $stockItem->name }}</strong>
                @if ($stockItem->sku)
                  <small class="text-muted">({{ $stockItem->sku }})</small>
                @endif
              </h5>
              <p class="mb-0 text-muted">
                Current Stock: <strong>{{ number_format($stockItem->quantity, 3) }}</strong>
                {{ $stockItem->unit ?? 'pcs' }}
                • Avg Cost: <strong>Rs. {{ number_format($stockItem->average_cost, 2) }}</strong>
                • Value: <strong>Rs. {{ number_format($stockItem->quantity * $stockItem->average_cost, 2) }}</strong>
              </p>
            </div>
            <a href="{{ route('departments.stock.index', $department) }}" class="btn btn-primary">
              <i class="bx bx-arrow-back me-1"></i> Back to Stock
            </a>
          </div>

          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-bordered">
                <thead class="table-dark">
                  <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <th>Balance After</th>
                    <th>Note</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $balance = 0;
                    $previousMovements = \App\Models\StockMovement::where('stock_item_id', $stockItem->id)
                        ->where(function ($q) use ($movements) {
                            if ($movements->count() > 0) {
                                $first = $movements->first();
                                $q->where('moved_at', '<', $first->moved_at)->orWhere(function ($qq) use ($first) {
                                    $qq->where('moved_at', $first->moved_at)->where('id', '<', $first->id);
                                });
                            }
                        })
                        ->sum(\DB::raw("CASE WHEN type = 'in' THEN quantity ELSE -quantity END"));

                    $balance += $previousMovements;
                  @endphp

                  @forelse($movements as $movement)
                    @php
                      $balance += $movement->type === 'in' ? $movement->quantity : -$movement->quantity;
                    @endphp
                    <tr class="{{ $movement->type === 'in' ? 'table-success' : 'table-danger' }}">
                      <td>{{ $movement->moved_at }}</td>
                      <td>
                        <span class="badge {{ $movement->type === 'in' ? 'bg-success' : 'bg-danger' }}">
                          {{ ucfirst($movement->type) }}
                        </span>
                      </td>
                      <td class="text-end">
                        <strong>{{ $movement->type === 'in' ? '+' : '-' }}{{ number_format($movement->quantity, 3) }}</strong>
                      </td>
                      <td class="text-end">Rs. {{ number_format($movement->unit_price, 2) }}</td>
                      <td class="text-end">Rs. {{ number_format($movement->quantity * $movement->unit_price, 2) }}</td>
                      <td class="text-end fw-bold text-primary">
                        {{ number_format($balance, 3) }}
                      </td>
                      <td><small class="text-muted">{{ $movement->note ?: '—' }}</small></td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bx bx-package bx-lg"></i><br>
                        No stock movements recorded yet.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            <div class="mt-4">
              {{ $movements->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
