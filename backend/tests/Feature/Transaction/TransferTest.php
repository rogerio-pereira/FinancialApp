<?php

namespace Tests\Feature\Transaction;

use App\Model\User;
use Tests\TestCase;
use App\Model\BankAccount;
use App\Model\Category;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransferTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function aUserCanCreateATransacation()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Category::class)->create();
        $account1 = factory(BankAccount::class)->create();
        $account2 = factory(BankAccount::class)->create();
        $data = [
            'from' => 1,
            'to' => 2,
            'category_id' => 1,
            'due_at' => Carbon::now()->toDateString(),
            'amount' => 50,
            'payed' => true
        ];

        $request = $this->post('/api/transactions/new/transfer', $data);

        $request->assertCreated()
            ->assertJson([
                [
                    'id' => 1,
                    'description' => 'Transfer from '.$account1->name.' to '.$account2->name,
                    'amount' => '50.00',
                    'type' => 'Expense',
                    'is_transfer' => true,
                    'due_at' => $data['due_at'],
                    'category_id' => 1,
                    'account_id' => 1,
                    'first_transaction' => 1,
                    'payed' => true,
                ],
                [
                    'id' => 2,
                    'description' => 'Transfer from '.$account1->name.' to '.$account2->name,
                    'amount' => '50.00',
                    'type' => 'Income',
                    'is_transfer' => true,
                    'due_at' => $data['due_at'],
                    'category_id' => 1,
                    'account_id' => 2,
                    'first_transaction' => 1,
                    'payed' => true,
                ]
            ]);

        $this->assertDatabaseHas('transactions', [
            'id' => 1,
            'description' => 'Transfer from '.$account1->name.' to '.$account2->name,
            'amount' => 50,
            'type' => 'Expense',
            'is_transfer' => true,
            'due_at' => $data['due_at'].' 00:00:00',
            'category_id' => 1,
            'account_id' => 1,
            'first_transaction' => 1,
            'payed' => true,
        ]);
        $this->assertDatabaseHas('transactions', [
            'id' => 2,
            'description' => 'Transfer from '.$account1->name.' to '.$account2->name,
            'amount' => 50,
            'type' => 'Income',
            'is_transfer' => true,
            'due_at' => $data['due_at'].' 00:00:00',
            'category_id' => 1,
            'account_id' => 2,
            'first_transaction' => 1,
            'payed' => true,
        ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateATransacationWithoutAllRequiredFields()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            // 'from' => 1,
            // 'to' => 2,
            // 'category_id' => 1,
            // 'due_at' => Carbon::now()->toDateString(),
            // 'amount' => 50,
            // 'payed' => true
        ];

        $request = $this->post('/api/transactions/new/transfer', $data);

        $request->assertStatus(422)
            ->assertJson([ 
                'errors' => [
                    'from' => [
                        'The from field is required.'
                    ],
                    'to' => [
                        'The to field is required.'
                    ],
                    'category_id' => [
                        'The category id field is required.'
                    ],
                    'due_at' => [
                        'The due at field is required.'
                    ],
                    'amount' => [
                        'The amount field is required.'
                    ],
                    'payed' => [
                        'The payed field is required.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateATransacationWithWrongFromAccount()
    {
        factory(Category::class)->create();
        $account1 = factory(BankAccount::class)->create();
        $account2 = factory(BankAccount::class)->create();
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'from' => 'from',
            'to' => 2,
            'category_id' => 1,
            'due_at' => Carbon::now()->toDateString(),
            'amount' => 50,
            'payed' => true
        ];

        $request = $this->post('/api/transactions/new/transfer', $data);

        $request->assertStatus(422)
            ->assertJson([ 
                'errors' => [
                    'from' => [
                        'The from field must be numeric.',
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateATransacationWithInvalidFromAccount()
    {
        factory(Category::class)->create();
        $account1 = factory(BankAccount::class)->create();
        $account2 = factory(BankAccount::class)->create();
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'from' => 3,
            'to' => 2,
            'category_id' => 1,
            'due_at' => Carbon::now()->toDateString(),
            'amount' => 50,
            'payed' => true
        ];

        $request = $this->post('/api/transactions/new/transfer', $data);

        $request->assertStatus(422)
            ->assertJson([ 
                'errors' => [
                    'from' => [
                        "This account doesn't exists.",
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateATransacationWithWrongToAccount()
    {
        factory(Category::class)->create();
        $account1 = factory(BankAccount::class)->create();
        $account2 = factory(BankAccount::class)->create();
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'from' => 1,
            'to' => 'to',
            'category_id' => 1,
            'due_at' => Carbon::now()->toDateString(),
            'amount' => 50,
            'payed' => true
        ];

        $request = $this->post('/api/transactions/new/transfer', $data);

        $request->assertStatus(422)
            ->assertJson([ 
                'errors' => [
                    'to' => [
                        'The to field must be numeric.',
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateATransacationWithInvalidToAccount()
    {
        factory(Category::class)->create();
        $account1 = factory(BankAccount::class)->create();
        $account2 = factory(BankAccount::class)->create();
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'from' => 1,
            'to' => 3,
            'category_id' => 1,
            'due_at' => Carbon::now()->toDateString(),
            'amount' => 50,
            'payed' => true
        ];

        $request = $this->post('/api/transactions/new/transfer', $data);

        $request->assertStatus(422)
            ->assertJson([ 
                'errors' => [
                    'to' => [
                        "This account doesn't exists.",
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateATransacationWithSameFromAndToAccount()
    {
        factory(Category::class)->create();
        $account1 = factory(BankAccount::class)->create();
        $account2 = factory(BankAccount::class)->create();
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'from' => 1,
            'to' => 1,
            'category_id' => 1,
            'due_at' => Carbon::now()->toDateString(),
            'amount' => 50,
            'payed' => true
        ];

        $request = $this->post('/api/transactions/new/transfer', $data);

        $request->assertStatus(422)
            ->assertJson([ 
                'errors' => [
                    'to' => [
                        "This fields must be different",
                    ],
                    'from' => [
                        "This fields must be different",
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateATransacationWithWrongCategory()
    {
        factory(Category::class)->create();
        $account1 = factory(BankAccount::class)->create();
        $account2 = factory(BankAccount::class)->create();
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'from' => 1,
            'to' => 2,
            'category_id' => 'category',
            'due_at' => Carbon::now()->toDateString(),
            'amount' => 50,
            'payed' => true
        ];

        $request = $this->post('/api/transactions/new/transfer', $data);

        $request->assertStatus(422)
            ->assertJson([ 
                'errors' => [
                    'category_id' => [
                        'The category id field must be numeric.',
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateATransacationWithInvalidToCategory()
    {
        factory(Category::class)->create();
        $account1 = factory(BankAccount::class)->create();
        $account2 = factory(BankAccount::class)->create();
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'from' => 1,
            'to' => 2,
            'category_id' => 2,
            'due_at' => Carbon::now()->toDateString(),
            'amount' => 50,
            'payed' => true
        ];

        $request = $this->post('/api/transactions/new/transfer', $data);

        $request->assertStatus(422)
            ->assertJson([ 
                'errors' => [
                    'category_id' => [
                        "This category doesn't exists.",
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateATransacationWithWrongAmount()
    {
        factory(Category::class)->create();
        $account1 = factory(BankAccount::class)->create();
        $account2 = factory(BankAccount::class)->create();
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'from' => 1,
            'to' => 2,
            'category_id' => 1,
            'due_at' => Carbon::now()->toDateString(),
            'amount' => 'amount',
            'payed' => true
        ];

        $request = $this->post('/api/transactions/new/transfer', $data);

        $request->assertStatus(422)
            ->assertJson([ 
                'errors' => [
                    'amount' => [
                        'The amount field must be numeric.',
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateATransacationWithAmountBellowZero()
    {
        factory(Category::class)->create();
        $account1 = factory(BankAccount::class)->create();
        $account2 = factory(BankAccount::class)->create();
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'from' => 1,
            'to' => 2,
            'category_id' => 1,
            'due_at' => Carbon::now()->toDateString(),
            'amount' => 0,
            'payed' => true
        ];

        $request = $this->post('/api/transactions/new/transfer', $data);

        $request->assertStatus(422)
            ->assertJson([ 
                'errors' => [
                    'amount' => [
                        'The amount must be at least 1.',
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateATransacationWithWrongPayed()
    {
        factory(Category::class)->create();
        $account1 = factory(BankAccount::class)->create();
        $account2 = factory(BankAccount::class)->create();
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'from' => 1,
            'to' => 2,
            'category_id' => 1,
            'due_at' => Carbon::now()->toDateString(),
            'amount' => 'amount',
            'payed' => 'payed'
        ];

        $request = $this->post('/api/transactions/new/transfer', $data);

        $request->assertStatus(422)
            ->assertJson([ 
                'errors' => [
                    'payed' => [
                        'The payed field must be true or false.',
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateATransacationWithWrongDate()
    {
        factory(Category::class)->create();
        $account1 = factory(BankAccount::class)->create();
        $account2 = factory(BankAccount::class)->create();
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'from' => 1,
            'to' => 2,
            'category_id' => 1,
            'due_at' => 'date',
            'amount' => 50,
            'payed' => 'payed'
        ];

        $request = $this->post('/api/transactions/new/transfer', $data);

        $request->assertStatus(422)
            ->assertJson([ 
                'errors' => [
                    'due_at' => [
                        'The due at is not a valid date.',
                    ],
                ]
            ]);
    }
}
