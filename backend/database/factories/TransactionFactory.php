<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Transaction;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {
    $type = [
        'Income',
        'Expense',
    ];

    return [
        'description' => $faker->word(),
        'amount' => rand(50,100),
        'type' => $type[rand(0,1)],
        'due_at' => $faker->dateTimeBetween(Carbon::now()->startOfYear(), Carbon::now()->endOfYear(), null),
        'category_id' => 1,
        'account_id' => 1,
        'payed' => 0,
    ];
});
