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
        factory(Transaction::class)->create([
            'type' => 'Income',
            'due_at' => Carbon::now()->toDateString(),
            'payed' => 0,
        ]);
        factory(Transaction::class)->create([
            'type' => 'Income',
            'due_at' => Carbon::now()->toDateString(),
            'payed' => 1,
        ]);
        factory(Transaction::class)->create([
            'type' => 'Expense',
            'due_at' => Carbon::now()->toDateString(),
            'payed' => 0,
        ]);
        factory(Transaction::class)->create([
            'type' => 'Expense',
            'due_at' => Carbon::now()->toDateString(),
            'payed' => 1,
        ]);
        factory(Transaction::class)->create([
            'type' => 'Transfer',
            'due_at' => Carbon::now()->toDateString(),
            'payed' => 0,
        ]);
        factory(Transaction::class)->create([
            'type' => 'Transfer',
            'due_at' => Carbon::now()->toDateString(),
            'payed' => 1,
        ]);
    }
}