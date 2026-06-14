<?php

namespace App\Http\Controllers\dashboard;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\StockItem;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

class Analytics extends Controller
{
  public function index(Request $request)
  {
    $departments = Department::orderBy('name')->get();
    $selectedDepartment = $request->input('department_id');

    // Last 12 months - always full array
    $months = [];
    for ($i = 11; $i >= 0; $i--) {
      $months[] = Carbon::now()->subMonths($i)->format('M Y');
    }

    $salesData = [];
    $expensesData = [];

    foreach ($months as $monthStr) {
      $start = Carbon::createFromFormat('M Y', $monthStr)->startOfMonth();
      $end = $start->copy()->endOfMonth();

      $salesQuery = Sale::whereBetween('sale_date', [$start, $end]);
      $expensesQuery = Expense::whereBetween('spent_at', [$start, $end]);

      if ($selectedDepartment) {
        $salesQuery->where('department_id', $selectedDepartment);
        $expensesQuery->where('department_id', $selectedDepartment);
      }

      $salesData[] = (float) ($salesQuery->sum('total') ?? 0);
      $expensesData[] = (float) ($expensesQuery->sum('amount') ?? 0);
    }

    // Donut chart totals
    $salesTotal = Sale::when($selectedDepartment, fn($q) => $q->where('department_id', $selectedDepartment))
      ->sum('total') ?? 0;

    $expensesTotal = Expense::when($selectedDepartment, fn($q) => $q->where('department_id', $selectedDepartment))
      ->sum('amount') ?? 0;

    $invoicesTotal = Invoice::when($selectedDepartment, fn($q) => $q->where('department_id', $selectedDepartment))
      ->sum('total') ?? 0;

    $stockValue = StockItem::when($selectedDepartment, fn($q) => $q->where('department_id', $selectedDepartment))
      ->sum(DB::raw('quantity * average_cost')) ?? 0;

    $profit = $salesTotal - $expensesTotal;

    $donutData = [
      round($profit, 2),
      round($expensesTotal, 2),
      round($invoicesTotal, 2),
      round($stockValue, 2)
    ];

    $donutLabels = ['Profit', 'Expenses', 'Invoices', 'Stock Value'];

    // Department sales distribution - ONLY when viewing ALL departments
    $departmentSalesData = null;

    if (!$selectedDepartment) {
      $departmentSalesData = Department::query()
        ->leftJoin('sales', 'departments.id', '=', 'sales.department_id')
        ->select(
          'departments.name',
          DB::raw('COALESCE(SUM(sales.total), 0) as total')
        )
        ->groupBy('departments.id', 'departments.name')
        ->orderByDesc('total')
        ->get();
    }

    return view('content.dashboard.dashboards-analytics', compact(
      'departments',
      'months',
      'salesData',
      'expensesData',
      'donutData',
      'donutLabels',
      'selectedDepartment',
      'departmentSalesData'
    ));
  }

  public function pdf(Request $request)
  {
    $selectedDepartment = $request->input('department_id');
    $departmentName = $selectedDepartment
      ? Department::find($selectedDepartment)?->name ?? 'Unknown'
      : 'All Departments';

    $months = [];
    for ($i = 11; $i >= 0; $i--) {
      $months[] = Carbon::now()->subMonths($i)->format('M Y');
    }

    $salesData = $expensesData = [];
    foreach ($months as $monthStr) {
      $start = Carbon::createFromFormat('M Y', $monthStr)->startOfMonth();
      $end = $start->copy()->endOfMonth();

      $salesQuery = Sale::whereBetween('sale_date', [$start, $end]);
      $expensesQuery = Expense::whereBetween('spent_at', [$start, $end]);

      if ($selectedDepartment) {
        $salesQuery->where('department_id', $selectedDepartment);
        $expensesQuery->where('department_id', $selectedDepartment);
      }

      $salesData[] = (float) ($salesQuery->sum('total') ?? 0);
      $expensesData[] = (float) ($expensesQuery->sum('amount') ?? 0);
    }

    $salesTotal = array_sum($salesData);
    $expensesTotal = array_sum($expensesData);
    $profit = $salesTotal - $expensesTotal;

    $averageMonthlySales = $salesTotal / 12;
    $averageMonthlyExpense = $expensesTotal / 12;

    $bestMonthSales = max($salesData);
    $worstMonthSales = min($salesData);
    $bestMonthIndex = array_search($bestMonthSales, $salesData);
    $worstMonthIndex = array_search($worstMonthSales, $salesData);

    $pdf = Pdf::loadView('content.dashboard.analytics-pdf', compact(
      'departmentName',
      'months',
      'salesData',
      'expensesData',
      'salesTotal',
      'expensesTotal',
      'profit',
      'averageMonthlySales',
      'averageMonthlyExpense',
      'bestMonthSales',
      'worstMonthSales',
      'bestMonthIndex',
      'worstMonthIndex'
    ))
      ->setPaper('a4', 'portrait');

    return $pdf->download('analytics-report-' . now()->format('Y-m-d') . '.pdf');
  }
}
