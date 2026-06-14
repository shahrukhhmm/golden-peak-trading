{{-- resources/views/content/backend/invoice/show.blade.php --}}
@extends('layouts/contentNavbarLayout')

@section('title', 'Invoice #' . $invoice->invoice_number . ' - ' . $department->name)

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('departments.index') }}">Departments</a></li>
      <li class="breadcrumb-item"><a href="{{ route('departments.invoices.index', $department) }}">Invoices</a></li>
      <li class="breadcrumb-item active">#{{ $invoice->invoice_number }}</li>
    </ol>
  </nav>

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div>
            <h4 class="mb-0">
              INVOICE <strong>#{{ $invoice->invoice_number }}</strong>
              <span class="badge bg-label-success ms-2">{{ $invoice->currency }}</span>
            </h4>
            <small class="text-muted">
              Date: {{ $invoice->invoice_date->format('d M Y') }}
              @if ($invoice->creator)
                • Created by: {{ $invoice->creator->name }}
              @endif
            </small>
          </div>
          <div>
            <a href="{{ route('departments.invoices.pdf', [$department, $invoice]) }}" target="_blank"
              class="btn btn-primary">
              <i class="bx bx-printer"></i> Print / PDF
            </a>
            <a href="{{ route('departments.invoices.index', $department) }}" class="btn btn-label-secondary ms-2">
              ← Back to Invoices
            </a>
          </div>
        </div>

        <div class="card-body">
          <div class="row mb-4">
            <div class="col-md-6">
              <h6>Bill To:</h6>
              <p class="mb-1">
                <strong>{{ $invoice->customer?->name ?? 'Walk-in Customer' }}</strong>
              </p>
              @if ($invoice->customer)
                <p class="mb-0">{{ $invoice->customer->phone ?? '' }}</p>
                <p class="mb-0">{{ $invoice->customer->address ?? '' }}</p>
              @endif
            </div>
            <div class="col-md-6 text-end">
              <h6>Department:</h6>
              <p><strong>{{ $department->name }}</strong></p>
              @if ($invoice->description)
                <p class="mt-3"><em>"{{ $invoice->description }}"</em></p>
              @endif
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-bordered">
              <thead class="table-light">
                <tr>
                  <th width="50">#</th>
                  <th>Description</th>
                  <th width="120" class="text-end">Qty</th>
                  <th width="150" class="text-end">Rate</th>
                  <th width="150" class="text-end">Amount</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($invoice->items as $i => $item)
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->description }}</td>
                    <td class="text-end">{{ number_format($item->quantity, 3) }}</td>
                    <td class="text-end">{{ number_format($item->rate, 2) }}</td>
                    <td class="text-end"><strong>{{ number_format($item->total, 2) }}</strong></td>
                  </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="4" class="text-end"><strong>Subtotal</strong></td>
                  <td class="text-end"><strong>Rs. {{ number_format($invoice->subtotal, 2) }}</strong></td>
                </tr>
                <tr>
                  <td colspan="4" class="text-end"><strong>Tax</strong></td>
                  <td class="text-end"><strong>Rs. {{ number_format($invoice->tax, 2) }}</strong></td>
                </tr>
                <tr class="table-primary">
                  <td colspan="4" class="text-end">
                    <h5 class="mb-0">Total Amount</h5>
                  </td>
                  <td class="text-end">
                    <h5 class="mb-0 text-primary">Rs. {{ number_format($invoice->total, 2) }}</h5>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>

          @if ($invoice->attachments->count() > 0)
            <div class="mt-4">
              <h6>Attachments ({{ $invoice->attachments->count() }})</h6>
              <div class="row">
                @foreach ($invoice->attachments as $att)
                  <div class="col-md-3 mb-3">
                    <a href="{{ Storage::url($att->path) }}" target="_blank" class="text-decoration-none">
                      <div class="border rounded p-3 text-center bg-light">
                        @if (str_contains($att->mime, 'pdf'))
                          <i class="bx bxs-file-pdf text-danger" style="font-size: 40px;"></i>
                        @else
                          <i class="bx bxs-file-image text-success" style="font-size: 40px;"></i>
                        @endif
                        <p class="mb-0 small mt-2">{{ Str::limit($att->filename, 20) }}</p>
                      </div>
                    </a>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        </div>

        <div class="card-footer text-center">
          <small class="text-muted">
            Thank you for your business!
          </small>
        </div>
      </div>
    </div>
  </div>
@endsection
