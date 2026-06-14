<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
  public function index(Department $department)
  {
    return view('content.backend.customer.index', compact('department'));
  }

  public function data(Department $department)
  {
    $customers = Customer::forDepartment($department->id)
      ->orderByRaw('department_id IS NULL DESC') // Global first
      ->orderBy('name')
      ->get();

    return response()->json([
      'data' => $customers->map(function ($c) {
        return [
          'id'        => $c->id,
          'name'      => $c->name . ($c->code ? " <small class='text-muted'>($c->code)</small>" : ''),
          'phone'     => $c->phone ?: '—',
          'address'   => $c->address ? substr(strip_tags($c->address), 0, 50) . (strlen($c->address) > 50 ? '...' : '') : '—',
          'type'      => $c->department_id
            ? '<span class="badge bg-label-primary">Department</span>'
            : '<span class="badge bg-label-success">Global</span>',
          'action'    => '
                        <button class="btn btn-sm btn-primary edit-btn" data-id="' . $c->id . '">
                            <i class="bx bx-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn ms-1" data-id="' . $c->id . '">
                            <i class="bx bx-trash"></i>
                        </button>
                    '
        ];
      })
    ]);
  }

  public function store(Request $request, Department $department)
  {
    $request->validate([
      'name'    => [
        'required',
        'string',
        'max:255',
        Rule::unique('customers')->where(function ($query) use ($department) {
          return $query->where('department_id', $department->id);
        })->ignore($request->id)
      ],
      'code'    => 'nullable|string|max:50|unique:customers,code',
      'phone'   => 'nullable|string|max:20',
      'address' => 'nullable|string|max:500',
      'notes'   => 'nullable|string'
    ]);

    $data = $request->only(['name', 'code', 'phone', 'address', 'notes']);
    $data['department_id'] = $department->id; // Always belongs to current department

    if ($request->filled('id')) {
      $customer = Customer::findOrFail($request->id);
      $customer->update($data);
      $msg = 'Customer updated successfully!';
    } else {
      Customer::create($data);
      $msg = 'Customer added successfully!';
    }

    return response()->json(['success' => true, 'message' => $msg]);
  }

  public function edit(Department $department, Customer $customer)
  {
    // Allow edit only if customer belongs to this dept or is global
    if ($customer->department_id && $customer->department_id !== $department->id) {
      abort(404);
    }

    return response()->json($customer);
  }

  public function destroy(Department $department, Customer $customer)
  {
    if ($customer->department_id && $customer->department_id !== $department->id) {
      abort(404);
    }

    $customer->delete();
    return response()->json(['success' => true, 'message' => 'Customer deleted!']);
  }
}
