<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategoriesSeeder extends Seeder
{
  public function run()
  {
    $now = Carbon::now();

    // Get department IDs
    $departments = DB::table('departments')->pluck('id', 'key');

    /*
        |--------------------------------------------------------------------------
        | SALT MINES CATEGORIES
        |--------------------------------------------------------------------------
        */
    $saltMines = [
      'Explosives (per kg)',
      'Salt production',
      'Sales',
      'Government expenses',
      'Office salaries',
      'Mine labor',
      'Langar',
      'Medical',
      'Diesel and petrol',
      'Agents',
    ];

    foreach ($saltMines as $name) {
      DB::table('categories')->insert([
        'department_id' => $departments['salt_mines'],
        'name'          => $name,
        'unit'          => null,
        'created_at'    => $now,
        'updated_at'    => $now,
      ]);
    }

    /*
        |--------------------------------------------------------------------------
        | AGRICULTURE DEPARTMENT CATEGORIES
        |--------------------------------------------------------------------------
        */
    $agriculture = [
      'Expenses and salaries',
      'Diesel',
      'Fish feed',
      'Fertilizer',
      'Electricity',
      'Seed',
      'Mess',
      'Medical',
      'Petrol',
      'Other costs',
    ];

    foreach ($agriculture as $name) {
      DB::table('categories')->insert([
        'department_id' => $departments['agriculture'],
        'name'          => $name,
        'unit'          => null,
        'created_at'    => $now,
        'updated_at'    => $now,
      ]);
    }

    /*
        |--------------------------------------------------------------------------
        | INDUS SALT PLANT CATEGORIES
        |--------------------------------------------------------------------------
        */
    $indusSalt = [
      'Salt',
      'Vehicle rent (to Chakwal)',
      'Daily labor',
      'Loading labor',
      'Plant labor (180 per ton)',
      'Mess (daily)',
      'Iodine',
      'Bardana packing',
      'Electricity',
      'Petrol',
      'Medicine',
      'Packing material',
      'Salt packing',
    ];

    foreach ($indusSalt as $name) {
      DB::table('categories')->insert([
        'department_id' => $departments['indus_salt_plant'],
        'name'          => $name,
        'unit'          => null,
        'created_at'    => $now,
        'updated_at'    => $now,
      ]);
    }

    /*
        |--------------------------------------------------------------------------
        | SELF EXPENSES CATEGORIES
        |--------------------------------------------------------------------------
        */
    $selfExpenses = [
      'General expenses',
    ];

    foreach ($selfExpenses as $name) {
      DB::table('categories')->insert([
        'department_id' => $departments['self_expenses'],
        'name'          => $name,
        'unit'          => null,
        'created_at'    => $now,
        'updated_at'    => $now,
      ]);
    }
  }
}
