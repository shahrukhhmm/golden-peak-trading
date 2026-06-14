<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('stock_items', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('department_id');
      $table->string('sku')->nullable();
      $table->string('name');
      $table->string('unit')->nullable();
      $table->decimal('quantity', 20, 3)->default(0);
      $table->decimal('average_cost', 15, 2)->default(0);
      $table->timestamps();

      $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('stock_items');
  }
};
