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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies', 'id')->onDelete('cascade');
            $table->foreignId('employee_id')->nullable()->constrained('employee_details', 'id')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->decimal('extra', 10, 2)->nullable();
            $table->decimal('total_amount', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
