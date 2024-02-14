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
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('parent_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('basic_salary');
            $table->string('salary_name');
            $table->string('house_rent')->nullable();
            $table->string('house_rent_amount')->nullable();
            $table->string('medical_allowance')->nullable();
            $table->string('medical_allowance_amount')->nullable();
            $table->string('conveyance_allowance')->nullable();
            $table->string('conveyance_allowance_amount')->nullable();
            $table->string('food_allowance')->nullable();
            $table->string('food_allowance_amount')->nullable();
            $table->string('communication_allowance')->nullable();
            $table->string('communication_allowance_amount')->nullable();
            $table->string('other')->nullable();
            $table->string('other_amount')->nullable();
            $table->boolean('enabled');
            $table->index('company_id');
            $table->timestamps();
            $table->softDeletes();
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
