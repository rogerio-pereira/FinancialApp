<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecurringTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recurring_transactions', function (Blueprint $table) {$table->bigIncrements('id');
            $table->string('description');
            $table->decimal('amount');
            $table->string('type');
            $table->date('last_date');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('first_transaction');
            $table->timestamps();

            $table->foreign('category_id')
                ->references('id')
                ->on('categories');

            $table->foreign('account_id')
                ->references('id')
                ->on('bank_accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recurring_transactions');
    }
}
