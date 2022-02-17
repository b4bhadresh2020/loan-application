<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLoanRepaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('loan_repayments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->smallInteger('instalment');
            $table->decimal('emi_amount', 10, 2);

            $table->dateTime('due_date');
            $table->dateTime('payment_date')->nullable();
            $table->decimal('paid_amount', 10, 2)->nullable();
            $table->enum('status', ['pending', 'paid', 'partially_paid'])->default('pending');

            $table->foreignUuid('user_id')->nullable()->constrained('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreignUuid('loan_application_id')->nullable()->constrained('loan_applications')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('loan_repayments');
    }
}
