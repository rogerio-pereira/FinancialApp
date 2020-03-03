<?php

use App\Model\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'name' => 'Rogerio Pereira',
            'email' => 'rogerio@test.com',
            'password' => bcrypt('123456')
        ]);
    }
}
