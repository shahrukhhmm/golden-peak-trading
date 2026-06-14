<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
  protected $fillable = [
    'stock_item_id',
    'department_id',
    'type',
    'quantity',
    'unit_price',
    'moved_at',
    'note',
    'related_id',
    'related_type'
  ];

  public function item()
  {
    return $this->belongsTo(StockItem::class, 'stock_item_id');
  }
  public function department()
  {
    return $this->belongsTo(Department::class);
  }

  public function scopeWithRunningBalance($query, $stockItemId)
  {
    return $query->select('*')
      ->selectRaw('(
            SELECT COALESCE(SUM(CASE WHEN type = "in" THEN quantity ELSE -quantity END), 0)
            FROM stock_movements sm2
            WHERE sm2.stock_item_id = ?
              AND (sm2.moved_at < stock_movements.moved_at
                   OR (sm2.moved_at = stock_movements.moved_at AND sm2.id <= stock_movements.id))
        ) as balance', [$stockItemId]);
  }
}
