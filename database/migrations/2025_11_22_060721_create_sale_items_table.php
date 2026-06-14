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
    Schema::create('sale_items', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('sale_id');
      $table->string('description');
      $table->decimal('quantity', 12, 3)->default(1);
      $table->decimal('rate', 15, 2)->default(0);
      $table->decimal('total', 15, 2)->default(0);
      $table->timestamps();

      $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('sale_items');
  }
};
