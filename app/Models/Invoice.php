<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
  protected $fillable = [
    'department_id',
    'customer_id',
    'invoice_number',
    'invoice_date',
    'description',
    'subtotal',
    'tax',
    'total',
    'currency',
    'created_by'
  ];

  protected $casts = [
    'invoice_date' => 'date',
    'subtotal' => 'decimal:2',
    'tax' => 'decimal:2',
    'total' => 'decimal:2'
  ];

  public function department()
  {
    return $this->belongsTo(Department::class);
  }
  public function customer()
  {
    return $this->belongsTo(Customer::class);
  }
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }
  public function items()
  {
    return $this->hasMany(InvoiceItem::class);
  }
  public function attachments()
  {
    return $this->hasMany(InvoiceAttachment::class);
  }

  // In your Invoice model
  public static function generateInvoiceNumber()
  {
    $year = now()->format('Y');
    $last = self::whereYear('created_at', $year)
      ->orderBy('id', 'desc')
      ->first();

    $number = $last ? ((int)substr($last->invoice_number, -5) + 1) : 1;
    return 'INV-' . $year . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);
  }
}
