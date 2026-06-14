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
    Schema::create('expenses', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('department_id');
      $table->unsignedBigInteger('category_id')->nullable();
      $table->decimal('amount', 15, 2)->default(0);
      $table->date('spent_at')->index();
      $table->string('currency', 10)->default('PKR');
      $table->text('note')->nullable();
      $table->unsignedBigInteger('created_by')->nullable(); // user
      $table->timestamps();

      $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
      $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
      $table->index(['department_id', 'spent_at']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('expenses');
  }
};
