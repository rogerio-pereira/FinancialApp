<?php

use Carbon\Carbon;
use App\Model\Category;
use App\Model\Transaction;
use Illuminate\Database\Seeder;
use App\Model\Useful\DateConversion;

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
        //51
        factory(Transaction::class)->create([
            'description' => 'Income',
            'type' => 'Income',
            'due_at' => Carbon::now()->toDateString(),
            'payed' => 0,
        ]);
        factory(Transaction::class)->create([
            'description' => 'Income',
            'type' => 'Income',
            'due_at' => Carbon::now()->toDateString(),
            'payed' => 1,
        ]);
        //53
        factory(Transaction::class)->create([
            'description' => 'Expense',
            'type' => 'Expense',
            'due_at' => Carbon::now()->toDateString(),
            'payed' => 0,
        ]);
        factory(Transaction::class)->create([
            'description' => 'Expense',
            'type' => 'Expense',
            'due_at' => Carbon::now()->toDateString(),
            'payed' => 1,
        ]);
        //55
        factory(Transaction::class)->create([
            'description' => 'Transfer',
            'type' => 'Expense',
            'is_transfer' => true,
            'amount' => 50,
            'due_at' => Carbon::now()->toDateString(),
            'payed' => 1,
            'first_transaction' => 55
        ]);
        factory(Transaction::class)->create([
            'description' => 'Transfer',
            'type' => 'Income',
            'is_transfer' => true,
            'amount' => 50,
            'due_at' => Carbon::now()->toDateString(),
            'payed' => 1,
            'first_transaction' => 55
        ]);
        //57
        $repeatedDate = Carbon::now()->firstOfMonth()->toDateString();
        for($i=0; $i<3; $i++) {
            factory(Transaction::class)->create([
                'description' => 'Repeat',
                'type' => 'Income',
                'amount' => 50,
                'due_at' => $repeatedDate,
                'payed' => 0,
                'first_transaction' => 57
            ]);

            $repeatedDate = DateConversion::newDateByPeriod($repeatedDate, 'Biweekly')->toDateString();
        }
        //60
    }
}
