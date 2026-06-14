<!DOCTYPE html>
<html>

<head>
  <style>
    body {
      font-family: DejaVu Sans;
    }

    .header {
      text-align: center;
      margin-bottom: 30px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      border: 1px solid #ddd;
      padding: 8px;
    }

    th {
      background: #f4f4f4;
    }
  </style>
</head>

<body>
  <div class="header">
    <h1>INVOICE</h1>
    <h2>{{ $invoice->invoice_number }}</h2>
    <p>Date: {{ $invoice->invoice_date->format('d M Y') }}</p>
  </div>

  <p><strong>Customer:</strong> {{ $invoice->customer?->name ?? 'Walk-in' }}</p>

  <table>
    <thead>
      <tr>
        <th>Description</th>
        <th>Qty</th>
        <th>Rate</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($invoice->items as $item)
        <tr>
          <td>{{ $item->description }}</td>
          <td>{{ $item->quantity }}</td>
          <td>{{ number_format($item->rate, 2) }}</td>
          <td>{{ number_format($item->total, 2) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div style="margin-top: 20px; text-align: right;">
    <p>Subtotal: Rs. {{ number_format($invoice->subtotal, 2) }}</p>
    <p>Tax: Rs. {{ number_format($invoice->tax, 2) }}</p>
    <h3>Total: Rs. {{ number_format($invoice->total, 2) }}</h3>
  </div>
</body>

</html>
