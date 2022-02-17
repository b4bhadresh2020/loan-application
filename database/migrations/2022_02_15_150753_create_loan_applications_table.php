<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('user_id')->nullable()->constrained('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreignUuid('approver_id')->nullable()->constrained('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->decimal('amount', 10, 2);
            $table->smallInteger('tenure');
            $table->decimal('interest', 6, 2);
            $table->enum('status', ['applied', 'approved', 'rejected', 'closed'])->default('applied');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('loan_applications');
    }
}
