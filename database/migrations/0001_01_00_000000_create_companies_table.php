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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('contact_email');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->time('checkin_start')->nullable();
            $table->time('checkin_end')->nullable();
            $table->integer('checkin_tolerance')->default(0);
            $table->time('checkout_start')->nullable();
            $table->time('checkout_end')->nullable();
            $table->string('company_code')->unique()->nullable();
            $table->string('company_invite')->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
