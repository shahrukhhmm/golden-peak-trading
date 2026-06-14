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
    Schema::create('categories', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('department_id');
      $table->string('name');
      $table->string('unit')->nullable(); // e.g., per kg, per ton
      $table->text('notes')->nullable();
      $table->timestamps();

      $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
      $table->unique(['department_id', 'name']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('categories');
  }
};
