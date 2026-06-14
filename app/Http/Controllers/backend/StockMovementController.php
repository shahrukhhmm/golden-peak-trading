<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\StockItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
  public function add(Department $department, StockItem $stockItem)
  {
    return view('content.backend.stock.add', compact('department', 'stockItem'));
  }

  public function storeIn(Request $request, Department $department, StockItem $stockItem)
  {
    $request->validate([
      'quantity' => 'required|numeric|min:0.001',
      'unit_price' => 'required|numeric|min:0',
      'moved_at' => 'required|date',
      'note' => 'nullable|string'
    ]);

    DB::transaction(function () use ($request, $stockItem, $department) {
      $oldQty = $stockItem->quantity;
      $oldCost = $stockItem->average_cost;
      $newQty = $request->quantity;
      $newCost = $request->unit_price;

      $totalQty = $oldQty + $newQty;
      $totalCost = ($oldQty * $oldCost) + ($newQty * $newCost);
      $avgCost = $totalQty > 0 ? $totalCost / $totalQty : 0;

      $stockItem->update([
        'quantity' => $totalQty,
        'average_cost' => $avgCost
      ]);

      StockMovement::create([
        'stock_item_id' => $stockItem->id,
        'department_id' => $department->id,
        'type' => 'in',
        'quantity' => $newQty,
        'unit_price' => $newCost,
        'moved_at' => $request->moved_at,
        'note' => $request->note
      ]);
    });

    return back()->with('success', 'Stock added!');
  }

  public function issue(Department $department, StockItem $stockItem)
  {
    return view('content.backend.stock.issue', compact('department', 'stockItem'));
  }

  public function storeOut(Request $request, Department $department, StockItem $stockItem)
  {
    $request->validate([
      'quantity' => 'required|numeric|min:0.001|lte:' . $stockItem->quantity,
      'moved_at' => 'required|date',
      'note' => 'nullable|string'
    ]);

    DB::transaction(function () use ($request, $stockItem, $department) {
      $stockItem->decrement('quantity', $request->quantity);

      StockMovement::create([
        'stock_item_id' => $stockItem->id,
        'department_id' => $department->id,
        'type' => 'out',
        'quantity' => $request->quantity,
        'unit_price' => $stockItem->average_cost,
        'moved_at' => $request->moved_at,
        'note' => $request->note
      ]);
    });

    return response()->json(['success' => true]);
  }

  public function history(Department $department, StockItem $stockItem)
  {
    $movements = StockMovement::where('stock_item_id', $stockItem->id)
      ->orderByDesc('moved_at')
      ->orderByDesc('id')
      ->paginate(25);

    return view('content.backend.stock.history', compact('department', 'stockItem', 'movements'));
  }
}
