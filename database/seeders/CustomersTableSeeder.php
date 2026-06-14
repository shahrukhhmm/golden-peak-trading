<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomersTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $now = Carbon::now();

    DB::table('customers')->insert([
      [
        'department_id' => null,
        'name' => 'Gulistan (16)',
        'code' => 'GUL16',
        'created_at' => $now,
        'updated_at' => $now,
      ],
      [
        'department_id' => null,
        'name' => 'Haji Sardar and Akhtar (7 Num Mine)',
        'code' => 'HS7',
        'created_at' => $now,
        'updated_at' => $now,
      ],
      [
        'department_id' => null,
        'name' => 'Muhammad Yousaf (14 Num Mine)',
        'code' => 'MY14',
        'created_at' => $now,
        'updated_at' => $now,
      ],
      [
        'department_id' => null,
        'name' => 'Baqir (Mine No 4)',
        'code' => 'BAQ4',
        'created_at' => $now,
        'updated_at' => $now,
      ],
    ]);
  }
}
