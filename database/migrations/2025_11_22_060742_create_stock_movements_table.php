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
    Schema::create('stock_movements', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('stock_item_id');
      $table->unsignedBigInteger('department_id');
      $table->enum('type', ['in', 'out'])->index();
      $table->decimal('quantity', 20, 3)->default(0);
      $table->decimal('unit_price', 15, 2)->default(0);
      $table->date('moved_at')->index();
      $table->text('note')->nullable();
      $table->unsignedBigInteger('related_id')->nullable(); // e.g., invoice_id or sale_id
      $table->string('related_type')->nullable();
      $table->timestamps();

      $table->foreign('stock_item_id')->references('id')->on('stock_items')->onDelete('cascade');
      $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('stock_movements');
  }
};
