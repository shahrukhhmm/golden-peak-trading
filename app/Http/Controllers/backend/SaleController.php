<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
  public function index(Department $department)
  {
    return view('content.backend.sale.index', compact('department'));
  }

  public function data(Department $department)
  {

    try {
      $sales = Sale::with(['items', 'creator'])
        ->where('department_id', $department->id)
        ->orderByDesc('sale_date')
        ->get();

      return response()->json([
        'data' => $sales->map(function ($sale) {
          $itemCount = $sale->items->count();
          $itemsPreview = $sale->items->take(3)->pluck('description')->implode(', ');
          $moreItems = $sale->items->count() > 3 ? ' + ' . ($sale->items->count() - 3) . ' more' : '';

          return [
            'id'         => $sale->id,
            'sale_date'  => $sale->sale_date->format('d M Y'),
            'total'      => '<strong class="text-success">' . number_format($sale->total, 2) . ' PKR</strong>',
            'items_count' => $itemCount,
            'items_preview' => $itemsPreview . $moreItems,
            'note'       => $sale->note ? substr(strip_tags($sale->note), 0, 50) . (strlen($sale->note) > 50 ? '...' : '') : '—',
            'creator'    => $sale->creator?->name ?? 'System',
            'action'     => '
                            <button class="btn btn-sm btn-primary edit-btn" data-id="' . $sale->id . '">
                                <i class="bx bx-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn ms-1" data-id="' . $sale->id . '">
                                <i class="bx bx-trash"></i>
                            </button>
                        '
          ];
        })
      ]);
    } catch (\Exception $e) {
      return response()->json(['data' => [], 'error' => 'Failed to load sales'], 500);
    }
  }

  public function store(Request $request, Department $department)
  {
    try {
      $request->validate([
        'sale_date' => 'required|date',
        'note'      => 'nullable|string|max:1000',
        'items'     => 'required|array|min:1',
        'items.*.description' => 'required|string|max:255',
        'items.*.quantity'    => 'required|numeric|min:0.001',
        'items.*.rate'        => 'required|numeric|min:0.01'
      ]);

      DB::beginTransaction();

      $data = [
        'department_id' => $department->id,
        'sale_date'     => $request->sale_date,
        'note'          => $request->note,
        'created_by'    => Auth::id()
      ];

      if ($request->filled('id')) {
        // Update existing sale
        $sale = Sale::findOrFail($request->id);
        if ($sale->department_id !== $department->id) {
          return response()->json(['error' => 'Access denied'], 403);
        }

        // Delete old items
        $sale->items()->delete();

        // Update sale
        $sale->update($data);
      } else {
        // Create new sale
        $sale = Sale::create($data);
      }

      // Create new items
      $total = 0;
      foreach ($request->items as $itemData) {
        $itemTotal = $itemData['quantity'] * $itemData['rate'];
        SaleItem::create([
          'sale_id'     => $sale->id,
          'description' => $itemData['description'],
          'quantity'    => $itemData['quantity'],
          'rate'        => $itemData['rate'],
          'total'       => $itemTotal
        ]);
        $total += $itemTotal;
      }

      // Update sale total
      $sale->update(['total' => $total]);

      DB::commit();

      return response()->json([
        'success' => true,
        'message' => $request->filled('id') ? 'Sale updated successfully!' : 'Sale recorded successfully!',
        'sale_id' => $sale->id
      ]);
    } catch (ValidationException $e) {
      DB::rollBack();
      return response()->json([
        'success' => false,
        'errors'  => $e->errors(),
        'message' => 'Validation failed'
      ], 422);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json([
        'success' => false,
        'message' => 'Something went wrong: ' . $e->getMessage()
      ], 500);
    }
  }

  public function edit(Department $department, Sale $sale)
  {
    if ($sale->department_id !== $department->id) {
      return response()->json(['error' => 'Not found'], 404);
    }

    return response()->json([
      'id'        => $sale->id,
      'sale_date' => $sale->sale_date->format('Y-m-d'),
      'note'      => $sale->note,
      'items'     => $sale->items->map(function ($item) {
        return [
          'description' => $item->description,
          'quantity'    => $item->quantity,
          'rate'        => $item->rate
        ];
      })
    ]);
  }

  public function destroy(Department $department, Sale $sale)
  {
    if ($sale->department_id !== $department->id) {
      return response()->json(['error' => 'Not found'], 404);
    }

    $sale->delete();

    return response()->json([
      'success' => true,
      'message' => 'Sale deleted successfully!'
    ]);
  }
}
