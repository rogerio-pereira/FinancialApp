<?php

namespace Tests\Feature\Category;

use App\Model\User;
use Tests\TestCase;
use App\Model\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function aUserCanGetAllCategories()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $category = factory(Category::class)->create();

        $response = $this->get('/api/categories');

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([[
                'id' => 1,
                'name' => $category->name,
            ]]);
    }

    /**
     * @test
     */
    public function aUserCanGetAllCategoriesSortedByNameAsc()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        
        factory(Category::class)->create(['name' => 'Category B']);
        factory(Category::class)->create(['name' => 'Category A']);

        $response = $this->get('/api/categories');

        $response->assertOk()
            ->assertJsonCount(2)
            ->assertJson([
                [
                    'id' => 2,
                    'name' => 'Category A',
                ],
                [
                    'id' => 1,
                    'name' => 'Category B',
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCanCreateACategory()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $category = [
            'name' => 'Category Test', 
        ];

        //POST
        $request = $this->post('/api/categories', $category);

        $request->assertCreated()
            ->assertJson([
                'id' => 1,
                'name' => 'Category Test',
            ]);

        $this->assertDatabaseHas('categories', $category);
    }

    /**
     * @test
     */
    public function aUserCanGetACategory()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $category = factory(Category::class)->create();

        $response = $this->get('/api/categories/1');

        $response->assertOk()
            ->assertJson([
                'id' => 1,
                'name' => $category->name,
            ]);
    }

    /**
     * @test
     */
    public function createCategoryValidationsFailWithNoName()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $category = [
            'name' => '', 
        ];

        //POST
        $request = $this->post('/api/categories', $category);

        $request->assertStatus(422)
            ->assertJson([ 
                'errors' => [
                    'name' => [
                        'The name field is required.'
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCanUpdateACategory()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $category = factory(Category::class)->create();

        //PUT
        $request = $this->put('/api/categories/1', ['name' => 'Update Category']);

        $request->assertOk()
            ->assertJson([
                'id' => 1,
                'name' => 'Update Category',
            ]);

        $response = $this->get('/api/categories');

        $response->assertOk()
            ->assertJsonCount(1);
    }

    /**
     * @test
     */
    public function aUserCanDeleteACategory()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $category = factory(Category::class)->create();

        //DELETE
        $request = $this->delete('/api/categories/1');

        $request->assertOk();

        $response = $this->get('/api/categories');

        $response->assertOk()
            ->assertJsonCount(0);
    }

    /**
     * @test
     */
    public function CategoriesCanBeConvertedIdNameArray()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Category::class)->create(['name' => 'Category B']);
        factory(Category::class)->create(['name' => 'Category A']);

        //GET
        $response = $this->get('/api/categories/combobox');

        $response->assertOk()
            ->assertJsonCount(2)
            ->assertJson([
                [
                    'id' => 2,
                    'name' => 'Category A',
                ],
                [
                    'id' => 1,
                    'name' => 'Category B',
                ],
            ]);
    }
}
