<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\BankAccount;
use Faker\Generator as Faker;

$factory->define(BankAccount::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(3, true),
        'initialBalance' => rand(50,100)
    ];
});
