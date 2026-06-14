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
    Schema::create('invoices', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('department_id');
      $table->unsignedBigInteger('customer_id')->nullable();
      $table->string('invoice_number')->unique();
      $table->date('invoice_date')->index();
      $table->text('description')->nullable();
      $table->decimal('subtotal', 15, 2)->default(0);
      $table->decimal('tax', 15, 2)->default(0);
      $table->decimal('total', 15, 2)->default(0);
      $table->string('currency', 10)->default('PKR');
      $table->unsignedBigInteger('created_by')->nullable();
      $table->timestamps();

      $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
      $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete();
      $table->index(['department_id', 'invoice_date']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('invoices');
  }
};
