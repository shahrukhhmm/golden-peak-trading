<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
  protected $fillable = [
    'department_id',
    'category_id',
    'amount',
    'spent_at',
    'currency',
    'note',
    'receipt',
    'created_by'
  ];

  protected $casts = [
    'spent_at' => 'date',
    'amount'   => 'decimal:2'
  ];

  public function department()
  {
    return $this->belongsTo(Department::class);
  }

  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }
}
