<?php

use App\Model\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Category::class)->create([
            'name' => 'Rent',
        ]);
        factory(Category::class)->create([
            'name' => 'Electric',
        ]);
        factory(Category::class)->create([
            'name' => 'Water',
        ]);
        factory(Category::class)->create([
            'name' => 'Internet',
        ]);
        factory(Category::class)->create([
            'name' => 'Cellphone',
        ]);
        factory(Category::class)->create([
            'name' => 'Car Insurance',
        ]);
        factory(Category::class)->create([
            'name' => 'Car',
        ]);
        factory(Category::class)->create([
            'name' => 'Gas',
        ]);
        factory(Category::class)->create([
            'name' => 'Entertainment',
        ]);
        factory(Category::class)->create([
            'name' => 'Supermarket',
        ]);
        factory(Category::class)->create([
            'name' => 'Hosting',
        ]);
        factory(Category::class)->create([
            'name' => 'Fun',
        ]);
    }
}
