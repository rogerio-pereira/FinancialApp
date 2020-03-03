<?php

use App\Model\BankAccount;
use Illuminate\Database\Seeder;

class BankAcountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(BankAccount::class, 3)->create();
    }
}
