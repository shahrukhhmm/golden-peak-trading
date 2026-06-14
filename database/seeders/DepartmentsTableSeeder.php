<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartmentsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $now = Carbon::now();
    DB::table('departments')->insert([
      [
        'key' => 'salt_mines',
        'name' => 'Salt Mines (Sahadat Ali Khan and Sons)',
        'description' => 'Salt Mines',
        'created_at' => $now,
        'updated_at' => $now,
      ],
      [
        'key' => 'self_expenses',
        'name' => 'Self Expenses (Sardar Ali Raza)',
        'description' => 'Self Expenses',
        'created_at' => $now,
        'updated_at' => $now,
      ],
      [
        'key' => 'indus_salt_plant',
        'name' => 'Indus Salt Plant (Talagang Road, Chakwal)',
        'description' => 'Indus Salt Plant',
        'created_at' => $now,
        'updated_at' => $now,
      ],
      [
        'key' => 'agriculture',
        'name' => 'Agriculture Department',
        'description' => 'Agriculture',
        'created_at' => $now,
        'updated_at' => $now,
      ],
    ]);
  }
}
