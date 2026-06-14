<?php

namespace App\Http\Controllers\backend;

use App\Models\Invoice;
use App\Models\Customer;
// use Barryvdh\DomPDF\PDF;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Department;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use App\Models\InvoiceAttachment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
  public function index(Department $department)
  {
    $customers = Customer::forDepartment($department->id)->orderBy('name')->get();
    return view('content.backend.invoice.index', compact('department', 'customers'));
  }

  public function data(Department $department)
  {
    $invoices = Invoice::with(['customer', 'creator'])
      ->where('department_id', $department->id)
      ->latest('invoice_date')
      ->get();

    return response()->json([
      'data' => $invoices->map(function ($inv) use ($department) {
        return [
          'id'             => $inv->id,
          'invoice_number' => '<strong>' . $inv->invoice_number . '</strong>',
          'customer'       => $inv->customer?->name ?? 'Walk-in',
          'invoice_date'   => $inv->invoice_date->format('d M Y'),
          'total'          => 'Rs. ' . number_format($inv->total, 2),
          'items_count'    => $inv->items()->count(),
          'creator'        => $inv->creator?->name ?? 'System',
          'action'         => '
                    <a href="' . route('departments.invoices.show', [$department, $inv]) . '" class="btn btn-sm btn-info">
                        View
                    </a>
                    <button class="btn btn-sm btn-primary edit-btn ms-1" data-id="' . $inv->id . '">
                        Edit
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn ms-1" data-id="' . $inv->id . '">
                        Delete
                    </button>
                '
        ];
      })
    ]);
  }

  public function store(Request $request, Department $department)
  {
    $request->validate([
      'customer_id' => 'nullable|exists:customers,id',
      'invoice_date' => 'required|date',
      'description' => 'nullable|string',
      'tax' => 'required|numeric|min:0',
      'items' => 'required|array|min:1',
      'items.*.description' => 'required|string',
      'items.*.quantity' => 'required|numeric|min:0.001',
      'items.*.rate' => 'required|numeric|min:0',
      'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
    ]);

    DB::transaction(function () use ($request, $department) {
      $isEdit = $request->filled('id') && $request->id > 0;

      $invoice = $isEdit
        ? Invoice::findOrFail($request->id)
        : new Invoice();

      // Only generate number on create
      if (!$isEdit) {
        $invoice->invoice_number = Invoice::generateInvoiceNumber();
      }

      $invoice->department_id = $department->id;
      $invoice->customer_id = $request->customer_id ?: null;
      $invoice->invoice_date = $request->invoice_date;
      $invoice->description = $request->description;
      $invoice->tax = $request->tax;
      $invoice->currency = 'PKR';
      $invoice->created_by = Auth::id();
      $invoice->save();
      if ($isEdit) {
        $invoice->items()->delete();
        foreach ($invoice->attachments as $att) {
          Storage::disk('public')->delete($att->path);
          $att->delete();
        }
      }

      $subtotal = 0;
      foreach ($request->items as $item) {
        $total = $item['quantity'] * $item['rate'];
        $subtotal += $total;

        InvoiceItem::create([
          'invoice_id' => $invoice->id,
          'description' => $item['description'],
          'quantity' => $item['quantity'],
          'rate' => $item['rate'],
          'total' => $total
        ]);
      }

      $invoice->subtotal = $subtotal;
      $invoice->total = $subtotal + $request->tax;
      $invoice->save();

      // Handle attachments
      if ($request->hasFile('attachments')) {
        foreach ($request->file('attachments') as $file) {
          $path = $file->store('invoices/' . $invoice->id, 'public');
          InvoiceAttachment::create([
            'invoice_id' => $invoice->id,
            'filename' => $file->getClientOriginalName(),
            'path' => $path,
            'mime' => $file->getMimeType(),
            'size' => $file->getSize()
          ]);
        }
      }
    });

    return response()->json([
      'success' => true,
      'message' => 'Invoice saved successfully!'
    ]);
  }

  public function show(Department $department, Invoice $invoice)
  {
    if ($invoice->department_id !== $department->id) abort(404);
    $invoice->load(['items', 'attachments', 'customer', 'creator']);
    return view('content.backend.invoice.show', compact('department', 'invoice'));
  }

  public function edit(Department $department, Invoice $invoice)
  {
    if ($invoice->department_id !== $department->id) abort(404);
    $invoice->load('items');
    return response()->json($invoice);
  }

  public function destroy(Department $department, Invoice $invoice)
  {
    if ($invoice->department_id !== $department->id) abort(404);
    foreach ($invoice->attachments as $att) {
      Storage::disk('public')->delete($att->path);
    }
    $invoice->delete();
    return response()->json(['success' => true]);
  }

  public function pdf(Department $department, Invoice $invoice)
  {
    if ($invoice->department_id !== $department->id) abort(404);
    $invoice->load(['items', 'customer', 'department', 'creator', 'attachments']);
    $pdf = PDF::loadView('content.backend.invoice.pdf', compact('invoice'));
    return $pdf->stream('invoice-' . $invoice->invoice_number . '.pdf');
  }
}
