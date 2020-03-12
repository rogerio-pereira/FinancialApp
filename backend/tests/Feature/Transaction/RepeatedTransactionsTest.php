<?php

namespace Tests\Feature\Transaction;

use Carbon\Carbon;
use App\Model\User;
use Tests\TestCase;
use App\Model\Category;
use App\Model\BankAccount;
use App\Model\Transaction;
use App\Model\Useful\DateConversion;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RepeatedTransactionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function aUserCanCreateARepeatedTransactionDaily()
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

            'repeat' => true,
            'repeatTimes' => 3,
            'period' => 'Daily'
        ];

        //POST
        $request = $this->post('/api/transactions', $transaction);

        $request->assertCreated()
            ->assertJson([
                [
                    'id' => 1,
                    'description' => 'Transaction',
                    'amount' => 50.00,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => true,
                    'first_transaction' => 1
                ],
                [
                    'id' => 2,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->addDay(1)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                    'first_transaction' => 1
                ],
                [
                    'id' => 3,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->addDay(2)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                    'first_transaction' => 1
                ],
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateARepeatedTransactionWithoutRepeatTimes()
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

            'repeat' => true,
            // 'repeatTimes' => 3,
            'period' => 'Daily'
        ];

        //POST
        $request = $this->post('/api/transactions', $transaction);

        $request->assertStatus(422)
            ->assertJson([ 
                'errors' => [
                    'repeatTimes' => [
                        'The repeat times field is required when repeat is selected.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateARepeatedTransactionWithWrongRepeatTimes()
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

            'repeat' => true,
            'repeatTimes' => 'repeat',
            'period' => 'Daily'
        ];

        //POST
        $request = $this->post('/api/transactions', $transaction);

        $request->assertStatus(422)
            ->assertJson([ 
                'errors' => [
                    'repeatTimes' => [
                        'The repeat times must be a number.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateARepeatedTransactionWithoutPeriod()
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

            'repeat' => true,
            'repeatTimes' => 'repeat',
            //'period' => 'Daily'
        ];

        //POST
        $request = $this->post('/api/transactions', $transaction);

        $request->assertStatus(422)
            ->assertJson([ 
                'errors' => [
                    'period' => [
                        'The period between each transaction must be informed.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateARepeatedTransactionWithWrongPeriod()
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

            'repeat' => true,
            'repeatTimes' => 'repeat',
            'period' => 'Test'
        ];

        //POST
        $request = $this->post('/api/transactions', $transaction);

        $request->assertStatus(422)
            ->assertJson([ 
                'errors' => [
                    'period' => [
                        'The selected period is invalid.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCanCreateARepeatedTransactionWeekly()
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

            'repeat' => true,
            'repeatTimes' => 3,
            'period' => 'Weekly'
        ];

        //POST
        $request = $this->post('/api/transactions', $transaction);

        $request->assertCreated()
            ->assertJson([
                [
                    'id' => 1,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => true,
                    'first_transaction' => 1
                ],
                [
                    'id' => 2,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->addWeek(1)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                    'first_transaction' => 1
                ],
                [
                    'id' => 3,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->addWeek(2)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                    'first_transaction' => 1
                ],
            ]);
    }

    /**
     * @test
     */
    public function aUserCanCreateARepeatedTransactionBiweekly()
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

            'repeat' => true,
            'repeatTimes' => 3,
            'period' => 'Biweekly'
        ];

        //POST
        $request = $this->post('/api/transactions', $transaction);

        $request->assertCreated()
            ->assertJson([
                [
                    'id' => 1,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => true,
                    'first_transaction' => 1
                ],
                [
                    'id' => 2,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->addWeek(2)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                    'first_transaction' => 1
                ],
                [
                    'id' => 3,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->addWeek(4)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                    'first_transaction' => 1
                ],
            ]);
    }

    /**
     * @test
     */
    public function aUserCanCreateARepeatedTransactionMonthly()
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

            'repeat' => true,
            'repeatTimes' => 3,
            'period' => 'Monthly'
        ];

        //POST
        $request = $this->post('/api/transactions', $transaction);

        $request->assertCreated()
            ->assertJson([
                [
                    'id' => 1,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => true,
                    'first_transaction' => 1
                ],
                [
                    'id' => 2,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->addMonth(1)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                    'first_transaction' => 1
                ],
                [
                    'id' => 3,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->addMonth(2)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                    'first_transaction' => 1
                ],
            ]);
    }

    /**
     * @test
     */
    public function aUserCanCreateARepeatedTransactionQuarterly()
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

            'repeat' => true,
            'repeatTimes' => 3,
            'period' => 'Quarterly'
        ];

        //POST
        $request = $this->post('/api/transactions', $transaction);

        $request->assertCreated()
            ->assertJson([
                [
                    'id' => 1,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => true,
                    'first_transaction' => 1
                ],
                [
                    'id' => 2,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->addMonth(3)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                    'first_transaction' => 1
                ],
                [
                    'id' => 3,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->addMonth(6)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                    'first_transaction' => 1
                ],
            ]);
    }

    /**
     * @test
     */
    public function aUserCanCreateARepeatedTransactionSemiannually()
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

            'repeat' => true,
            'repeatTimes' => 3,
            'period' => 'Semiannually'
        ];

        //POST
        $request = $this->post('/api/transactions', $transaction);

        $request->assertCreated()
            ->assertJson([
                [
                    'id' => 1,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => true,
                    'first_transaction' => 1
                ],
                [
                    'id' => 2,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->addMonth(6)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                    'first_transaction' => 1
                ],
                [
                    'id' => 3,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->addMonth(12)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                    'first_transaction' => 1
                ],
            ]);
    }

    /**
     * @test
     */
    public function aUserCanCreateARepeatedTransactionAnnually()
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

            'repeat' => true,
            'repeatTimes' => 3,
            'period' => 'Annually'
        ];

        //POST
        $request = $this->post('/api/transactions', $transaction);

        $request->assertCreated()
            ->assertJson([
                [
                    'id' => 1,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => true,
                    'first_transaction' => 1
                ],
                [
                    'id' => 2,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->addYear(1)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                    'first_transaction' => 1
                ],
                [
                    'id' => 3,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->addYear(2)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                    'first_transaction' => 1
                ],
            ]);
    }

    /**
     * @test
     */
    public function aUserCanDeleteOnlyOneRepeatedTransaction()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Category::class)->create();
        factory(BankAccount::class)->create();
        
        $repeatedDate = Carbon::now()->firstOfMonth()->toDateString();
        for($i=0; $i<3; $i++) {
            factory(Transaction::class)->create([
                'description' => 'Repeat',
                'type' => 'Income',
                'amount' => 50,
                'due_at' => $repeatedDate,
                'payed' => 0,
                'first_transaction' => 1
            ]);

            $repeatedDate = DateConversion::newDateByPeriod($repeatedDate, 'Biweekly')->toDateString();
        }

        $response = $this->get('/api/transactions');

        $response->assertOk()
            ->assertJsonCount(3)
            ->assertJson([
                [
                    'id' => 3,
                    'description' => 'Repeat',
                    'amount' => 50,
                    'type' => 'Income',
                    'due_at' => Carbon::now()->firstOfMonth()->addWeek(4)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                ],
                [
                    'id' => 2,
                    'description' => 'Repeat',
                    'amount' => 50,
                    'type' => 'Income',
                    'due_at' => Carbon::now()->firstOfMonth()->addWeek(2)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                ],
                [
                    'id' => 1,
                    'description' => 'Repeat',
                    'amount' => 50,
                    'type' => 'Income',
                    'due_at' => Carbon::now()->firstOfMonth()->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                ],
            ]);

        $request = $this->delete('/api/transactions/2/this');
        $request->assertOk();

        $response2 = $this->get('/api/transactions');
        $response2->assertOk()
            ->assertJsonCount(2)
            ->assertJson([
                [
                    'id' => 3,
                    'description' => 'Repeat',
                    'amount' => 50,
                    'type' => 'Income',
                    'due_at' => Carbon::now()->firstOfMonth()->addWeek(4)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                ],
                [
                    'id' => 1,
                    'description' => 'Repeat',
                    'amount' => 50,
                    'type' => 'Income',
                    'due_at' => Carbon::now()->firstOfMonth()->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                ],
            ]);
    }

    /**
     * @test
     */
    public function aUserCanDeleteThisAndNextRepeatedTransactions()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Category::class)->create();
        factory(BankAccount::class)->create();
        
        $repeatedDate = Carbon::now()->firstOfMonth()->toDateString();
        for($i=0; $i<3; $i++) {
            factory(Transaction::class)->create([
                'description' => 'Repeat',
                'type' => 'Income',
                'amount' => 50,
                'due_at' => $repeatedDate,
                'payed' => 0,
                'first_transaction' => 1
            ]);

            $repeatedDate = DateConversion::newDateByPeriod($repeatedDate, 'Biweekly')->toDateString();
        }

        $response = $this->get('/api/transactions');

        $response->assertOk()
            ->assertJsonCount(3)
            ->assertJson([
                [
                    'id' => 3,
                    'description' => 'Repeat',
                    'amount' => 50,
                    'type' => 'Income',
                    'due_at' => Carbon::now()->firstOfMonth()->addWeek(4)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                ],
                [
                    'id' => 2,
                    'description' => 'Repeat',
                    'amount' => 50,
                    'type' => 'Income',
                    'due_at' => Carbon::now()->firstOfMonth()->addWeek(2)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                ],
                [
                    'id' => 1,
                    'description' => 'Repeat',
                    'amount' => 50,
                    'type' => 'Income',
                    'due_at' => Carbon::now()->firstOfMonth()->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                ],
            ]);

        $request = $this->delete('/api/transactions/2/next');
        $request->assertOk();

        $response2 = $this->get('/api/transactions');
        $response2->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'description' => 'Repeat',
                    'amount' => 50,
                    'type' => 'Income',
                    'due_at' => Carbon::now()->firstOfMonth()->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                ],
            ]);
    }

    /**
     * @test
     */
    public function aUserCanDeleteAllRepeatedTransactions()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Category::class)->create();
        factory(BankAccount::class)->create();
        
        $repeatedDate = Carbon::now()->firstOfMonth()->toDateString();
        for($i=0; $i<3; $i++) {
            factory(Transaction::class)->create([
                'description' => 'Repeat',
                'type' => 'Income',
                'amount' => 50,
                'due_at' => $repeatedDate,
                'payed' => 0,
                'first_transaction' => 1
            ]);

            $repeatedDate = DateConversion::newDateByPeriod($repeatedDate, 'Biweekly')->toDateString();
        }

        $response = $this->get('/api/transactions');

        $response->assertOk()
            ->assertJsonCount(3)
            ->assertJson([
                [
                    'id' => 3,
                    'description' => 'Repeat',
                    'amount' => 50,
                    'type' => 'Income',
                    'due_at' => Carbon::now()->firstOfMonth()->addWeek(4)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                ],
                [
                    'id' => 2,
                    'description' => 'Repeat',
                    'amount' => 50,
                    'type' => 'Income',
                    'due_at' => Carbon::now()->firstOfMonth()->addWeek(2)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                ],
                [
                    'id' => 1,
                    'description' => 'Repeat',
                    'amount' => 50,
                    'type' => 'Income',
                    'due_at' => Carbon::now()->firstOfMonth()->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                ],
            ]);

        $request = $this->delete('/api/transactions/2/all');
        $request->assertOk();

        $response2 = $this->get('/api/transactions');
        $response2->assertOk()
            ->assertJsonCount(0)
            ->assertJson([]);
    }

    /**
     * @test
     */
    public function aUserCanDeleteFirstRepeatedTransactions()
    {

    }
}
