<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stock Report - {{ $department->name }}</title>
    <style>
        body { 
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif; 
            font-size: 12px; 
            color: #333;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
        }
        th, td { 
            border: 1px solid #bbb; 
            padding: 8px 10px; 
        }
        th { 
            background: #f5f5f5; 
            font-weight: bold;
        }
        .text-right { text-align: right !important; }
        .header { margin-bottom: 25px; }
        .title { margin: 0; color: #1a1a1a; }
        .info { color: #555; margin: 6px 0; }
        .no-data {
            text-align: center;
            padding: 40px 20px;
            color: #e74c3c;
            font-size: 14px;
            font-style: italic;
            border: 2px dashed #e74c3c;
            border-radius: 8px;
            margin: 30px 0;
        }
    </style>
</head>
<body>

<div class="header">
    <h2 class="title">Stock Report - {{ $department->name }}</h2>
    
    @if($has_date_filter)
        <p class="info">Period: <strong>{{ $start_date }}</strong> — <strong>{{ $end_date }}</strong></p>
    @else
        <p class="info">Showing all stock items (no date filter applied)</p>
    @endif
    
    <p class="info">Generated: {{ $generated_at }}</p>
</div>

@if($items->isEmpty())
    <div class="no-data">
        @if($has_date_filter)
            No stock items found in the selected period ({{ $start_date }} — {{ $end_date }}).
        @else
            No stock items found in this department.
        @endif
    </div>
@else
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Name</th>
                <th>Unit</th>
                <th class="text-right">Quantity</th>
                <th class="text-right">Avg Cost</th>
                <th class="text-right">Total Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item['sku'] }}</td>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['unit'] }}</td>
                <td class="text-right">{{ $item['quantity'] }}</td>
                <td class="text-right">Rs. {{ $item['avg_cost'] }}</td>
                <td class="text-right"><strong>Rs. {{ $item['total_value'] }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif

</body>
</html>