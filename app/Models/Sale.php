<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
  protected $fillable = [
    'department_id',
    'sale_date',
    'total',
    'note',
    'created_by'
  ];

  protected $casts = [
    'sale_date' => 'date',
    'total' => 'decimal:2'
  ];

  public function department()
  {
    return $this->belongsTo(Department::class);
  }

  public function items()
  {
    return $this->hasMany(SaleItem::class);
  }

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  // Calculate total from items
  public function getTotalAttribute()
  {
    return $this->items->sum('total');
  }
}
