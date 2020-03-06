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
        factory(Transaction::class)->create([
            'due_at' => Carbon::now()->startOfMonth()->subDay()
        ]);
        //First Day of Month (ID: 2)
        factory(Transaction::class)->create([
            'due_at' => Carbon::now()->startOfMonth()
        ]);
        //Today (ID: 3)
        factory(Transaction::class)->create([
            'due_at' => Carbon::now()
        ]);
        //FirstDayOfNextMonth (ID: 4)
        factory(Transaction::class)->create([
            'due_at' => Carbon::now()->endOfMonth()->addDay()
        ]);
        //LastDayOfMonth (ID: 5)
        factory(Transaction::class)->create([
            'due_at' => Carbon::now()->endOfMonth()
        ]);
    }
}
