<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
  protected $fillable = ['department_id', 'sku', 'name', 'unit', 'quantity', 'average_cost'];

  public function department()
  {
    return $this->belongsTo(Department::class);
  }
  public function movements()
  {
    return $this->hasMany(StockMovement::class);
  }
}
