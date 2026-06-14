<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
  protected $fillable = [
    'sale_id',
    'description',
    'quantity',
    'rate',
    'total'
  ];

  protected $casts = [
    'quantity' => 'decimal:3',
    'rate' => 'decimal:2',
    'total' => 'decimal:2'
  ];

  public function sale()
  {
    return $this->belongsTo(Sale::class);
  }

  // Auto-calculate total
  protected static function boot()
  {
    parent::boot();
    static::saving(function ($item) {
      $item->total = $item->quantity * $item->rate;
    });
  }
}
