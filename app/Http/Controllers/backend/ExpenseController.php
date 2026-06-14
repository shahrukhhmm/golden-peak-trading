<?php

namespace App\Http\Controllers\backend;

use App\Models\Expense;
use App\Models\Category;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ExpenseController extends Controller
{
  public function index(Department $department)
  {
    $categories = Category::where('department_id', $department->id)
      ->orderBy('name')
      ->get();

    return view('content.backend.expense.index', compact('department', 'categories'));
  }

  // app/Http/Controllers/backend/ExpenseController.php

  public function data(Department $department)
  {
    try {
      $expenses = Expense::with(['category', 'creator'])
        ->where('department_id', $department->id)
        ->orderByDesc('spent_at')
        ->get();

      $data = $expenses->map(function ($exp) {
        return [
          'id'       => $exp->id,
          'category' => $exp->category?->name ?? '<em class="text-muted">Uncategorized</em>',
          'amount'   => '<strong>' . number_format($exp->amount, 2) . ' PKR</strong>',
          'spent_at' => $exp->spent_at->format('d M Y'),
          'note'     => $exp->note
            ? substr(strip_tags($exp->note), 0, 60) . (strlen($exp->note) > 60 ? '...' : '')
            : '—',
          'receipt'  => $exp->receipt
            ? '<a href="' . asset('storage/' . $exp->receipt) . '" target="_blank" class="text-primary"><i class="bx bx-link-external"></i></a>'
            : '—',
          'creator'  => $exp->creator?->name ?? 'System',
          'action'   => '
                    <button class="btn btn-sm btn-primary edit-btn" data-id="' . $exp->id . '">
                        <i class="bx bx-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn ms-1" data-id="' . $exp->id . '">
                        <i class="bx bx-trash"></i>
                    </button>
                '
        ];
      });

      return response()->json([
        'data' => $data
      ]);
    } catch (\Exception $e) {
      Log::error('Expense data error: ' . $e->getMessage());
      return response()->json([
        'data' => [],
        'error' => 'Failed to load expenses'
      ], 500);
    }
  }

  public function store(Request $request, Department $department)
  {
    try {
      $request->validate([
        'category_id' => 'nullable|exists:categories,id',
        'amount'      => 'required|numeric|min:0.01',
        'spent_at'    => 'required|date',
        'note'        => 'nullable|string|max:1000',
        'receipt'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
      ]);

      $data = [
        'department_id' => $department->id,
        'category_id'   => $request->category_id,
        'amount'        => $request->amount,
        'spent_at'      => $request->spent_at,
        'currency'      => 'PKR',
        'note'          => $request->note,
        'created_by'    => Auth::id()
      ];

      // Handle file upload
      if ($request->hasFile('receipt')) {
        $path = $request->file('receipt')->store('receipts', 'public');
        $data['receipt'] = $path;
      }

      if ($request->filled('id')) {
        // Update existing
        $expense = Expense::findOrFail($request->id);
        if ($expense->department_id !== $department->id) {
          return response()->json(['error' => 'Access denied'], 403);
        }

        // Delete old receipt if new one uploaded
        if ($expense->receipt && $request->hasFile('receipt')) {
          Storage::disk('public')->delete($expense->receipt);
        }

        $expense->update($data);
        $message = 'Expense updated successfully!';
      } else {
        // Create new
        Expense::create($data);
        $message = 'Expense recorded successfully!';
      }

      return response()->json([
        'success' => true,
        'message' => $message
      ]);
    } catch (ValidationException $e) {
      return response()->json([
        'success' => false,
        'errors'  => $e->errors(),
        'message' => 'Validation failed'
      ], 422);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Something went wrong: ' . $e->getMessage()
      ], 500);
    }
  }

  public function edit(Department $department, Expense $expense)
  {
    if ($expense->department_id !== $department->id) {
      return response()->json(['error' => 'Not found'], 404);
    }

    return response()->json([
      'id'         => $expense->id,
      'category_id' => $expense->category_id,
      'amount'     => $expense->amount,
      'spent_at'   => $expense->spent_at->format('Y-m-d'),
      'note'       => $expense->note,
      'receipt'    => $expense->receipt
    ]);
  }

  public function destroy(Department $department, Expense $expense)
  {
    if ($expense->department_id !== $department->id) {
      return response()->json(['error' => 'Not found'], 404);
    }

    // Delete receipt file
    if ($expense->receipt) {
      Storage::disk('public')->delete($expense->receipt);
    }

    $expense->delete();

    return response()->json([
      'success' => true,
      'message' => 'Expense deleted successfully!'
    ]);
  }
}
