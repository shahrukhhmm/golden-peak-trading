<?php

namespace App\Http\Controllers\backend;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
  public function index()
  {
    return view('content.backend.department.index');
  }

  // DataTables data
  public function data()
  {
    $departments = Department::select(['id', 'key', 'name', 'description', 'created_at'])->get();

    return response()->json([
      'data' => $departments->map(function ($dept) {
        return [
          'id'          => $dept->id,
          'key'         => $dept->key,
          'name'        => $dept->name,
          'description' => $dept->description ?? '<em class="text-muted">No description</em>',
          'created_at'  => $dept->created_at->format('d M Y'),
          'action'      => '
                      <button class="btn btn-sm btn-primary edit-btn" data-id="' . $dept->id . '">
                          <i class="bx bx-edit"></i>
                      </button>
                      <button class="btn btn-sm btn-danger delete-btn ms-1" data-id="' . $dept->id . '">
                          <i class="bx bx-trash"></i>
                      </button>
                  '
        ];
      })
    ]);
  }

  // Store or Update
  public function store(Request $request)
  {
    $request->validate([
      'key'         => 'required|string|unique:departments,key,' . $request->id,
      'name'        => 'required|string|max:255',
      'description' => 'nullable|string',
    ]);

    Department::updateOrCreate(
      ['id' => $request->id],
      $request->only(['key', 'name', 'description'])
    );

    return response()->json(['success' => true, 'message' => 'Department saved successfully!']);
  }

  // Edit (fetch single record)
  public function edit($id)
  {
    $dept = Department::findOrFail($id);
    return response()->json($dept);
  }

  // Delete
  public function destroy($id)
  {
    Department::findOrFail($id)->delete();
    return response()->json(['success' => true, 'message' => 'Department deleted!']);
  }
}
