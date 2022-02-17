<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanTermsTable extends Migration
{
    public function up()
    {
        Schema::create('loan_terms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->smallInteger('start_weeks');
            $table->smallInteger('end_weeks');
            $table->decimal('interest', 6, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('loan_terms');
    }
}
