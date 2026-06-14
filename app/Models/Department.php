<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
  protected $fillable = ['key', 'name', 'description'];

  public function categories()
  {
    return $this->hasMany(Category::class);
  }

  public function expenses()
  {
    return $this->hasMany(Expense::class);
  }

  public function sales()
  {
    return $this->hasMany(Sale::class);
  }
}
