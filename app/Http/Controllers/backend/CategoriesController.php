<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Department;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
  public function index(Department $department)
  {
    // dd($department);
    $department->loadCount('categories');

    return view('content.backend.category.index', compact('department'));
  }

  public function data(Department $department)
  {
    $categories = Category::where('department_id', $department->id)
      ->select(['id', 'name', 'unit', 'notes', 'created_at']);

    return response()->json([
      'data' => $categories->get()->map(function ($cat) {
        return [
          'id'         => $cat->id,
          'name'       => $cat->name,
          'unit'       => $cat->unit ?? '<em class="text-muted">—</em>',
          'notes'      => $cat->notes
            ? substr(strip_tags($cat->notes), 0, 70) . (strlen(strip_tags($cat->notes)) > 70 ? '...' : '')
            : '<em class="text-muted">No notes</em>',
          'created_at' => $cat->created_at->format('d M Y'),
          'action'     => '
            <button class="btn btn-sm btn-primary edit-btn" data-id="' . $cat->id . '">
                <i class="bx bx-edit"></i>
            </button>
            <button class="btn btn-sm btn-danger delete-btn ms-1" data-id="' . $cat->id . '">
                <i class="bx bx-trash"></i>
            </button>'
        ];
      })
    ]);
  }

  public function store(Request $request, Department $department)
  {
    $request->validate([
      'name'  => 'required|string|max:255|unique:categories,name,NULL,id,department_id,' . $department->id,
      'unit'  => 'nullable|string|max:50',
      'notes' => 'nullable|string',
    ]);

    $data = $request->only(['name', 'unit', 'notes']);
    $data['department_id'] = $department->id;

    if ($request->filled('id')) {
      $category = Category::findOrFail($request->id);
      $category->update($data);
      $msg = 'Category updated!';
    } else {
      Category::create($data);
      $msg = 'Category created!';
    }

    return response()->json(['success' => true, 'message' => $msg]);
  }
  public function edit(Department $department, Category $category)
  {
    if ($category->department_id !== $department->id) {
      abort(404);
    }

    return response()->json($category);
  }

  public function destroy(Department $department, Category $category)
  {
    if ($category->department_id !== $department->id) {
      abort(404);
    }

    $category->delete();
    return response()->json(['success' => true, 'message' => 'Category deleted!']);
  }
}
