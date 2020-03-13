<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\RecurringTransaction;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(RecurringTransaction::class, function (Faker $faker) {
    return [
        'description' => $faker->word(),
        'amount' => rand(50,100),
        'type' => 'Expense',
        'last_date' => $faker->dateTimeBetween(Carbon::now()->startOfYear(), Carbon::now()->endOfYear(), null),
        'category_id' => 1,
        'account_id' => 1,
        'first_transaction' => 1,
        'period' => 'Monthly',
    ];
});