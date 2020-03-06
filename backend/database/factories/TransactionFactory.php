<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Transaction;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'description' => $faker->word(),
        'amount' => rand(50,100),
        'type' => 'Expense',
        'due_at' => Carbon::now()->toDateString(),
        'category_id' => 1,
        'account_id' => 1,
        'payed' => false,
    ];
});
