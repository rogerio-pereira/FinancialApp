<?php

namespace Tests\Feature\Transaction;

use App\Model\User;
use Tests\TestCase;
use App\Model\Category;
use App\Model\BankAccount;
use App\Model\Transaction;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function aUserCanGetAllTransactionsInCurrentMonth()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $category = factory(Category::class)->create();
        $account = factory(BankAccount::class)->create();

        //LastDayOfLastMonth (ID: 1)
        $lastMonth = factory(Transaction::class)->create([
            'due_at' => Carbon::now()->startOfMonth()->subDay()->toDateString(),
        ]);
        //First Day of Month (ID: 2)
        $firstOfMonth = factory(Transaction::class)->create([
            'due_at' => Carbon::now()->startOfMonth()->toDateString(),
        ]);
        //Today (ID: 3)
        $today = factory(Transaction::class)->create([
            'due_at' => Carbon::now()->toDateString(),
        ]);
        //FirstDayOfNextMonth (ID: 4)
        $nextMonth = factory(Transaction::class)->create([
            'due_at' => Carbon::now()->endOfMonth()->addDay()->toDateString(),
        ]);
        //LastDayOfMonth (ID: 5)
        $lastOfMonth = factory(Transaction::class)->create([
            'due_at' => Carbon::now()->endOfMonth()->toDateString(),
        ]);

        $response = $this->get('/api/transactions');

        $response->assertOk()
            ->assertJsonCount(3)
            ->assertJson([
                [
                    'id' => 5,
                    'description' => $lastOfMonth->description,
                    'amount' => $lastOfMonth->amount,
                    'type' => $lastOfMonth->type,
                    'due_at' => $lastOfMonth->due_at->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                    'category' => [
                        'id' => $category->id,
                        'name' => $category->name,
                    ],
                    'account' => [
                        'id' => $account->id,
                        'name' => $account->name,
                    ],
                ],
                [
                    'id' => 3,
                    'description' => $today->description,
                    'amount' => $today->amount,
                    'type' => $today->type,
                    'due_at' => $today->due_at->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                    'category' => [
                        'id' => $category->id,
                        'name' => $category->name,
                    ],
                    'account' => [
                        'id' => $account->id,
                        'name' => $account->name,
                    ],
                ],
                [
                    'id' => 2,
                    'description' => $firstOfMonth->description,
                    'amount' => $firstOfMonth->amount,
                    'type' => $firstOfMonth->type,
                    'due_at' => $firstOfMonth->due_at->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                    'category' => [
                        'id' => $category->id,
                        'name' => $category->name,
                    ],
                    'account' => [
                        'id' => $account->id,
                        'name' => $account->name,
                    ],
                ],
            ]);
    }

    /**
     * @test
     */
    public function aUserCanGetAllTransactionsInLastMonth()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $category = factory(Category::class)->create();
        $account = factory(BankAccount::class)->create();

        //LastDayOfLastMonth (ID: 1)
        $lastMonth = factory(Transaction::class)->create([
            'due_at' => Carbon::now()->startOfMonth()->subDay()
        ]);
        //First Day of Month (ID: 2)
        $firstOfMonth = factory(Transaction::class)->create([
            'due_at' => Carbon::now()->startOfMonth()
        ]);
        //Today (ID: 3)
        $today = factory(Transaction::class)->create([
            'due_at' => Carbon::now()
        ]);
        //FirstDayOfNextMonth (ID: 4)
        $nextMonth = factory(Transaction::class)->create([
            'due_at' => Carbon::now()->endOfMonth()->addDay()
        ]);
        //LastDayOfMonth (ID: 5)
        $lastOfMonth = factory(Transaction::class)->create([
            'due_at' => Carbon::now()->endOfMonth()
        ]);

        $response = $this->get('/api/transactions/'.(date('m')-1).'/'.date('Y'));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'description' => $lastMonth->description,
                    'amount' => $lastMonth->amount,
                    'type' => $lastMonth->type,
                    'due_at' => $lastMonth->due_at->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                    'category' => [
                        'id' => $category->id,
                        'name' => $category->name,
                    ],
                    'account' => [
                        'id' => $account->id,
                        'name' => $account->name,
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCanGetAllTransactionsInNextMonth()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $category = factory(Category::class)->create();
        $account = factory(BankAccount::class)->create();

        //LastDayOfLastMonth (ID: 1)
        $lastMonth = factory(Transaction::class)->create([
            'due_at' => Carbon::now()->startOfMonth()->subDay()
        ]);
        //First Day of Month (ID: 2)
        $firstOfMonth = factory(Transaction::class)->create([
            'due_at' => Carbon::now()->startOfMonth()
        ]);
        //Today (ID: 3)
        $today = factory(Transaction::class)->create([
            'due_at' => Carbon::now()
        ]);
        //FirstDayOfNextMonth (ID: 4)
        $nextMonth = factory(Transaction::class)->create([
            'due_at' => Carbon::now()->endOfMonth()->addDay()
        ]);
        //LastDayOfMonth (ID: 5)
        $lastOfMonth = factory(Transaction::class)->create([
            'due_at' => Carbon::now()->endOfMonth()
        ]);

        $response = $this->get('/api/transactions/'.(date('m')+1).'/'.date('Y'));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 4,
                    'description' => $nextMonth->description,
                    'amount' => $nextMonth->amount,
                    'type' => $nextMonth->type,
                    'due_at' => $nextMonth->due_at->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                    'category' => [
                        'id' => $category->id,
                        'name' => $category->name,
                    ],
                    'account' => [
                        'id' => $account->id,
                        'name' => $account->name,
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCanGetATransaction()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Category::class,2)->create();
        factory(BankAccount::class,2)->create();

        $transaction = factory(Transaction::class)->create([
            'due_at' => Carbon::now()->startOfMonth()->subDay()
        ]);

        //POST/
        $request = $this->get('/api/transactions/1');
        $request->assertOk()
            ->assertJson([
                'id' => 1,
                'description' => $transaction->description,
                'amount' => $transaction->amount,
                'type' => $transaction->type,
                'due_at' => $transaction->due_at->toDateString(),
                'category_id' => $transaction->category_id,
                'account_id' => $transaction->account_id,
                'payed' => $transaction->payed,
            ]);
    }

    /**
     * @test
     */
    public function aUserCanCreateAnUnpaidExpenseTransaction()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Category::class)->create();
        factory(BankAccount::class)->create();

        $transaction = [
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'due_at' => Carbon::now()->toDateString(),
            'category_id' => 1,
            'account_id' => 1,
            'payed' => false,
        ];

        //POST
        $request = $this->post('/api/transactions', $transaction);

        $request->assertCreated()
            ->assertJson([
                'id' => 1,
                'description' => 'Transaction',
                'amount' => 50,
                'type' => 'Expense',
                'due_at' => Carbon::now()->toDateString(),
                'category_id' => 1,
                'account_id' => 1,
                'payed' => false,
            ]);
    }

    /**
     * @test
     */
    public function aUserCanCreateAnPaidExpenseTransaction()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Category::class)->create();
        factory(BankAccount::class)->create();

        $transaction = [
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'due_at' => Carbon::now()->toDateString(),
            'category_id' => 1,
            'account_id' => 1,
            'payed' => true,
        ];

        //POST
        $request = $this->post('/api/transactions', $transaction);

        $request->assertCreated()
            ->assertJson([
                'id' => 1,
                'description' => 'Transaction',
                'amount' => 50,
                'type' => 'Expense',
                'due_at' => Carbon::now()->toDateString(),
                'category_id' => 1,
                'account_id' => 1,
                'payed' => true,
            ]);
    }

    /**
     * @test
     */
    public function aUserCanCreateAnUnpaidIncomeTransaction()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Category::class)->create();
        factory(BankAccount::class)->create();

        $transaction = [
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Income',
            'due_at' => Carbon::now()->toDateString(),
            'category_id' => 1,
            'account_id' => 1,
            'payed' => false,
        ];

        //POST
        $request = $this->post('/api/transactions', $transaction);

        $request->assertCreated()
            ->assertJson([
                'id' => 1,
                'description' => 'Transaction',
                'amount' => 50,
                'type' => 'Income',
                'due_at' => Carbon::now()->toDateString(),
                'category_id' => 1,
                'account_id' => 1,
                'payed' => false,
            ]);
    }

    /**
     * @test
     */
    public function aUserCanCreateAnPaidIncomeTransaction()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Category::class)->create();
        factory(BankAccount::class)->create();

        $transaction = [
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Income',
            'due_at' => Carbon::now()->toDateString(),
            'category_id' => 1,
            'account_id' => 1,
            'payed' => true,
        ];

        //POST
        $request = $this->post('/api/transactions', $transaction);

        $request->assertCreated()
            ->assertJson([
                'id' => 1,
                'description' => 'Transaction',
                'amount' => 50,
                'type' => 'Income',
                'due_at' => Carbon::now()->toDateString(),
                'category_id' => 1,
                'account_id' => 1,
                'payed' => true,
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateAnTransactionWithWrongType()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Category::class)->create();
        factory(BankAccount::class)->create();

        $transaction = [
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Test',
            'due_at' => Carbon::now()->toDateString(),
            'category_id' => 1,
            'account_id' => 1,
            'payed' => true,
        ];

        //POST
        $request = $this->post('/api/transactions', $transaction);

        $request->assertStatus(302);

        $this->assertDatabaseMissing('transactions', $transaction);
    }

    /**
     * @test
     */
    public function aUserCantCreateAnTransactionWithWrongCategory()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Category::class)->create();
        factory(BankAccount::class)->create();

        $transaction = [
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Test',
            'due_at' => Carbon::now()->toDateString(),
            'category_id' => 1,
            'account_id' => 1,
            'payed' => true,
        ];

        //POST
        $request = $this->post('/api/transactions', $transaction);

        $request->assertStatus(302);

        $this->assertDatabaseMissing('transactions', $transaction);
    }

    /**
     * @test
     */
    public function aUserCantCreateAnTransactionWithWrongAccount()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Category::class)->create();
        factory(BankAccount::class)->create();

        $transaction = [
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Test',
            'due_at' => Carbon::now()->toDateString(),
            'category_id' => 1,
            'account_id' => 2,
            'payed' => true,
        ];

        //POST
        $request = $this->post('/api/transactions', $transaction);

        $request->assertStatus(302);

        $this->assertDatabaseMissing('transactions', $transaction);
    }

    /**
     * @test
     */
    public function aUserCanUpdateTransaction()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Category::class,2)->create();
        factory(BankAccount::class,2)->create();

        $transaction1 = factory(Transaction::class)->create();

        //Data to be updated
        $transaction2 = [
            'description' => 'Transaction alt',
            'amount' => 100,
            'type' => 'Income',
            'due_at' => Carbon::now()->addDay(),
            'category_id' => 2,
            'account_id' => 2,
            'payed' => true,
        ];

        //POST
        $request = $this->put('/api/transactions/1', $transaction2);
        $request->assertOk();

        $response = $this->get('/api/transactions');
        $response->assertJsonCount(1);

        $this->assertDatabaseMissing('transactions', $transaction1->toArray());
    }

    /**
     * @test
     */
    public function aUserCanDeleteATransaction()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Category::class)->create();
        factory(BankAccount::class)->create();
        $transaction = factory(Transaction::class)->create();

        //DELETE
        $request = $this->delete('/api/transactions/1');

        $request->assertOk();

        $response = $this->get('/api/transactions');

        $response->assertOk()
            ->assertJsonCount(0);

        $this->assertDatabaseMissing('transactions', $transaction->toArray());
    }
}
