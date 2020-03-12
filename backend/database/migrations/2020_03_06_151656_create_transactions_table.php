<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description');
            $table->decimal('amount');
            $table->string('type')->default('Expense');
            $table->boolean('is_transfer')->default(false);
            $table->date('due_at');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('first_transaction')->nullable();
            $table->boolean('payed')->default(false);
            $table->timestamps();

            $table->foreign('category_id')
                ->references('id')
                ->on('categories');

            $table->foreign('account_id')
                ->references('id')
                ->on('bank_accounts');

            $table->foreign('first_transaction')
                ->references('id')
                ->on('transactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
