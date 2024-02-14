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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->nullable();

            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');

            $table->bigInteger('branch_id')->unsigned();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');

            $table->bigInteger('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');

            $table->bigInteger('interest')->unsigned()->nullable();
            $table->string('name');

            $table->date('loan_date');
            $table->date('from_date');

            $table->decimal('loan_amount', 20, 2);

            $table->integer('loan_installment');

            $table->enum('receive_loan', ['cash', 'bank cheque', 'salary'])->default('salary');

            $table->decimal('loan_installment_amount', 20, 2);

            $table->string('ad_type')->nullable();

            $table->string('give_type')->nullable();

            $table->string('bank_id')->nullable();

            $table->string('bank_name')->nullable();

            $table->string('branch_name')->nullable();

            $table->string('cheque_id')->nullable();

            $table->date('cheque_date')->nullable();

            $table->text('note')->nullable();

            $table->enum('loan_status', ['1', '2', '3'])->default('1');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
