<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
  protected $fillable = [
    'department_id',
    'name',
    'code',
    'phone',
    'address',
    'notes'
  ];

  public function department()
  {
    return $this->belongsTo(Department::class);
  }

  // Scope: Show global customers + those belonging to current department
  public function scopeForDepartment($query, $departmentId)
  {
    return $query->where(function ($q) use ($departmentId) {
      $q->where('department_id', $departmentId)
        ->orWhereNull('department_id');
    });
  }
}
