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
        Schema::create('employee_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id')->restrictOnDelete();
            $table->foreignId('department_id')->constrained('department_id', 'departments')->restrictOnDelete();
            $table->foreignId('position')->constrained('position_id', 'positions')->restrictOnDelete();
            $table->date('hire_date');
            $table->string('phone', 15);
            $table->string('address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_details');
    }
};
