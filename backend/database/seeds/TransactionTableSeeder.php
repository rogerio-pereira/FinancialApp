<?php

use Carbon\Carbon;
use App\Model\Category;
use App\Model\Transaction;
use Illuminate\Database\Seeder;

class TransactionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Transaction::class, 50)->create();
    }
}
