<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
  protected $fillable = ['invoice_id', 'description', 'quantity', 'rate', 'total'];

  protected static function boot()
  {
    parent::boot();
    static::saving(function ($item) {
      $item->total = $item->quantity * $item->rate;
    });
  }

  public function invoice()
  {
    return $this->belongsTo(Invoice::class);
  }
}
