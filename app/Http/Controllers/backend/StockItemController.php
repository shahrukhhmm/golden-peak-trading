<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class StockItemController extends Controller
{
  public function index(Department $department)
  {
    return view('content.backend.stock.index', compact('department'));
  }

  public function data(Department $department)
  {
    $items = StockItem::where('department_id', $department->id)
      ->orderBy('name')
      ->get();

    return response()->json([
      'data' => $items->map(function ($i) use ($department) { 
        return [
          'id'       => $i->id,
          'sku'      => $i->sku ?: '—',
          'name'     => '<strong>' . e($i->name) . '</strong>',
          'unit'     => $i->unit ?: 'pcs',
          'quantity' => '<span class="badge rounded-pill bg-label-' . ($i->quantity > 0 ? 'success' : 'danger') . '">'
            . number_format($i->quantity, 3) . '</span>',
          'avg_cost' => 'Rs. ' . number_format($i->average_cost, 2),
          'value'    => 'Rs. ' . number_format($i->quantity * $i->average_cost, 2),
          'actions'  => view('content.backend.stock.partials.actions', [
            'department' => $department,  // Now it's defined
            'i'          => $i
          ])->render(),
        ];
      })
    ]);
  }


  public function store(Request $request, Department $department)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'sku' => 'nullable|string|unique:stock_items,sku',
      'unit' => 'nullable|string|max:20'
    ]);

    StockItem::create([
      'department_id' => $department->id,
      'name' => $request->name,
      'sku' => $request->sku,
      'unit' => $request->unit ?: 'pcs'
    ]);

    return response()->json(['success' => true, 'message' => 'Item created!']);
  }

  public function pdf(Request $request, Department $department)
  {
      $startDate = $request->query('start_date');
      $endDate   = $request->query('end_date');

      $query = StockItem::where('department_id', $department->id)
          ->orderBy('name');
          
      if ($startDate && $endDate && $startDate <= $endDate) {
          $query->whereBetween('created_at', [
              $startDate . ' 00:00:00',
              $endDate   . ' 23:59:59'
          ]);
      }

      $items = $query->get()->map(function ($item) {
          return [
              'sku'         => $item->sku ?: '—',
              'name'        => $item->name,
              'unit'        => $item->unit ?: 'pcs',
              'quantity'    => number_format($item->quantity, 3),
              'avg_cost'    => number_format($item->average_cost, 2),
              'total_value' => number_format($item->quantity * $item->average_cost, 2),
          ];
      });

      $pdf = Pdf::loadView('content.backend.stock.pdf_report', [
          'department'     => $department,
          'items'          => $items,
          'start_date'     => $startDate,
          'end_date'       => $endDate,
          'generated_at'   => now()->format('d M Y H:i'),
          'has_date_filter' => $startDate && $endDate,
      ]);

      $filename = 'Stock-Report-' . str_replace(' ', '-', $department->name) . '-' . now()->format('Y-m-d') . '.pdf';

      return $pdf->stream($filename);
  }
}
