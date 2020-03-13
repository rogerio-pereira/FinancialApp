<?php

namespace Tests\Feature\Transaction;

use Carbon\Carbon;
use App\Model\User;
use Tests\TestCase;
use App\Model\Category;
use App\Model\BankAccount;
use App\Model\Transaction;
use App\Model\RecurringTransaction;
use App\Model\Useful\DateConversion;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecurringExpensesTest extends TestCase
{
    use RefreshDatabase;

    private $transaction = [
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'category_id' => 1,
            'account_id' => 1,
            'payed' => true,

            'recurring' => true,
        ];

    /**
     * @test
     * @dataProvider provideData
     */
    public function aUserCanCreateARecurringTransactions($date, $frequency, $repeatTimes)
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Category::class)->create();
        factory(BankAccount::class)->create();
        $date = Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');

        //POST
        $this->transaction['due_at'] = $date;
        $this->transaction['period'] = $frequency;
        $request = $this->post('/api/transactions', $this->transaction);

        $request->assertCreated()
            ->assertJsonCount($repeatTimes);
        $lastDate = $date;
        
        for($i=1; $i<=$repeatTimes; $i++) {
            $lastDate = $date;
            $payed = $i==1 ? true : false;

            $request->assertJsonFragment(
                [
                    'id' => $i,
                    'description' => 'Transaction',
                    'amount' => '50.00',
                    'type' => 'Expense',
                    'due_at' => $date,
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => $payed,
                    'first_transaction' => 1,
                    'is_recurring' => true,
                ]
            );

            $date = DateConversion::newDateByPeriod($date, $frequency)->toDateString();
        }

        $this->assertDatabaseHas('recurring_transactions', [
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'last_date' => Carbon::createFromFormat('Y-m-d', $lastDate)->format('Y-m-d').' 00:00:00',
            'category_id' => 1,
            'account_id' => 1,
            'first_transaction' => 1,
            'period' => $frequency,
        ]);
    }

    public function provideData()
    {
        return [
            ['2020-01-01', 'Daily', Carbon::createFromFormat('Y-m-d', '2020-01-01')->daysInYear],
            ['2019-01-01', 'Daily', Carbon::createFromFormat('Y-m-d', '2019-01-01')->daysInYear],

            ['2020-01-01', 'Weekly', Carbon::createFromFormat('Y-m-d', '2020-01-01')->weeksInYear+1],
            ['2019-01-01', 'Weekly', Carbon::createFromFormat('Y-m-d', '2019-01-01')->weeksInYear+1],
            ['2020-01-01', 'Biweekly', ((Carbon::createFromFormat('Y-m-d', '2020-01-01')->weeksInYear/2) +1)],
            ['2019-01-01', 'Biweekly', ((Carbon::createFromFormat('Y-m-d', '2019-01-01')->weeksInYear/2) +1)],

            ['2020-01-01', 'Monthly', 12],
            ['2020-02-01', 'Monthly', 11],
            ['2020-03-01', 'Monthly', 10],
            ['2020-04-01', 'Monthly', 9],
            ['2020-05-01', 'Monthly', 8],
            ['2020-06-01', 'Monthly', 7],
            ['2020-07-01', 'Monthly', 6],
            ['2020-08-01', 'Monthly', 5],
            ['2020-09-01', 'Monthly', 4],
            ['2020-10-01', 'Monthly', 3],
            ['2020-11-01', 'Monthly', 2],
            ['2020-12-01', 'Monthly', 1],
            
            ['2020-01-01', 'Quarterly', 4],
            ['2020-02-01', 'Quarterly', 4],
            ['2020-03-01', 'Quarterly', 4],
            ['2020-04-01', 'Quarterly', 3],
            ['2020-05-01', 'Quarterly', 3],
            ['2020-06-01', 'Quarterly', 3],
            ['2020-07-01', 'Quarterly', 2],
            ['2020-08-01', 'Quarterly', 2],
            ['2020-09-01', 'Quarterly', 2],
            ['2020-10-01', 'Quarterly', 1],
            ['2020-11-01', 'Quarterly', 1],
            ['2020-12-01', 'Quarterly', 1],

            ['2020-01-01', 'Semiannually', 2],
            ['2020-05-01', 'Semiannually', 2],
            ['2020-06-01', 'Semiannually', 2],
            ['2020-07-01', 'Semiannually', 1],

            ['2020-01-01', 'Annually', 1],
            ['2020-06-15', 'Annually', 1],
            ['2020-12-25', 'Annually', 1],
        ];
    }

    /**
     * @test
     */
    public function aUserCanDeleteASingleRecurringTransactionWithoutDeletingTheRecurringModel()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Category::class)->create();
        factory(BankAccount::class)->create();
        factory(Transaction::class)->create([
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'due_at' => '2020-01-01',
            'category_id' => 1,
            'account_id' => 1,
            'payed' => false,
            'first_transaction' => 1,
            'is_recurring' => true,
        ]);
        factory(Transaction::class)->create([
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'due_at' => '2020-06-01',
            'category_id' => 1,
            'account_id' => 1,
            'payed' => false,
            'first_transaction' => 1,
            'is_recurring' => true,
        ]);
        factory(RecurringTransaction::class)->create([
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'last_date' => '2020-06-01',
            'category_id' => 1,
            'account_id' => 1,
            'first_transaction' => 1,
            'period' => 'Semiannually'
        ]);

        $this->assertDatabaseHas('recurring_transactions', [
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'last_date' => '2020-06-01 00:00:00',
            'category_id' => 1,
            'account_id' => 1,
            'first_transaction' => 1,
            'period' => 'Semiannually'
        ]);

        $request = $this->delete('/api/transactions/1/this');
        $request->assertOk();

        $this->assertDatabaseHas('recurring_transactions', [
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'last_date' => '2020-06-01 00:00:00',
            'category_id' => 1,
            'account_id' => 1,
            'first_transaction' => 1,
            'period' => 'Semiannually'
        ]);
    }

    /**
     * @test
     */
    public function aUserCanDeleteThisAndNextRecurringTransactionsDeletingTheRecurringModel()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Category::class)->create();
        factory(BankAccount::class)->create();
        factory(Transaction::class)->create([
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'due_at' => '2020-01-01',
            'category_id' => 1,
            'account_id' => 1,
            'payed' => false,
            'first_transaction' => 1,
            'is_recurring' => true,
        ]);
        factory(Transaction::class)->create([
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'due_at' => '2020-06-01',
            'category_id' => 1,
            'account_id' => 1,
            'payed' => false,
            'first_transaction' => 1,
            'is_recurring' => true,
        ]);
        factory(RecurringTransaction::class)->create([
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'last_date' => '2020-06-01',
            'category_id' => 1,
            'account_id' => 1,
            'first_transaction' => 1,
            'period' => 'Semiannually'
        ]);

        $this->assertDatabaseHas('recurring_transactions', [
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'last_date' => '2020-06-01 00:00:00',
            'category_id' => 1,
            'account_id' => 1,
            'first_transaction' => 1,
            'period' => 'Semiannually'
        ]);

        $request = $this->delete('/api/transactions/1/next');
        $request->assertOk();

        $this->assertDatabaseMissing('recurring_transactions', [
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'last_date' => '2020-06-01 00:00:00',
            'category_id' => 1,
            'account_id' => 1,
            'first_transaction' => 1,
            'period' => 'Semiannually'
        ]);
    }

    /**
     * @test
     */
    public function aUserCanDeleteAllRecurringTransactionsDeletingTheRecurringModel()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Category::class)->create();
        factory(BankAccount::class)->create();
        factory(Transaction::class)->create([
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'due_at' => '2020-01-01',
            'category_id' => 1,
            'account_id' => 1,
            'payed' => false,
            'first_transaction' => 1,
            'is_recurring' => true,
        ]);
        factory(Transaction::class)->create([
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'due_at' => '2020-06-01',
            'category_id' => 1,
            'account_id' => 1,
            'payed' => false,
            'first_transaction' => 1,
            'is_recurring' => true,
        ]);
        factory(RecurringTransaction::class)->create([
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'last_date' => '2020-06-01',
            'category_id' => 1,
            'account_id' => 1,
            'first_transaction' => 1,
            'period' => 'Semiannually'
        ]);

        $this->assertDatabaseHas('recurring_transactions', [
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'last_date' => '2020-06-01 00:00:00',
            'category_id' => 1,
            'account_id' => 1,
            'first_transaction' => 1,
            'period' => 'Semiannually'
        ]);

        $request = $this->delete('/api/transactions/1/all');
        $request->assertOk();

        $this->assertDatabaseMissing('recurring_transactions', [
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'last_date' => '2020-06-01 00:00:00',
            'category_id' => 1,
            'account_id' => 1,
            'first_transaction' => 1,
            'period' => 'Semiannually'
        ]);
    }

    /**
     * @test
     */
    public function aUserCanUpdateASingleRecurringTransactionWithoutUpdatingTheRecurringModel()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Category::class, 2)->create();
        factory(BankAccount::class, 2)->create();
        factory(Transaction::class)->create([
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'due_at' => Carbon::now()->startOfMonth(),
            'category_id' => 1,
            'account_id' => 1,
            'payed' => false,
            'first_transaction' => 1,
            'is_recurring' => true,
        ]);
        factory(Transaction::class)->create([
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'due_at' => Carbon::now()->startOfMonth()->addDay(14),
            'category_id' => 1,
            'account_id' => 1,
            'payed' => false,
            'first_transaction' => 1,
            'is_recurring' => true,
        ]);
        factory(Transaction::class)->create([
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'due_at' => Carbon::now()->startOfMonth()->addDay(30),
            'category_id' => 1,
            'account_id' => 1,
            'payed' => false,
            'first_transaction' => 1,
            'is_recurring' => true,
        ]);
        factory(RecurringTransaction::class)->create([
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'last_date' => Carbon::now()->startOfMonth()->addDay(30),
            'category_id' => 1,
            'account_id' => 1,
            'first_transaction' => 1,
            'period' => 'Biweekly'
        ]);

        $response = $this->get('/api/transactions');
        $response->assertOk()
            ->assertJsonCount(3)
            ->assertJson([
                [
                    'id' => 3,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->startOfMonth()->addDay(30)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                ],
                [
                    'id' => 2,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->startOfMonth()->addDay(14)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                ],
                [
                    'id' => 1,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->startOfMonth()->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                ],
            ]);

        $this->assertDatabaseHas('recurring_transactions', [
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'last_date' => Carbon::now()->startOfMonth()->addDay(30)->toDateString().' 00:00:00',
            'category_id' => 1,
            'account_id' => 1,
            'first_transaction' => 1,
            'period' => 'Biweekly'
        ]);

        $data = [
            'description' => 'Transaction Update',
            'amount' => 100,
            'type' => 'Income',
            'category_id' => 2,
            'account_id' => 2,
            'repeatCount' => 'this'
        ];

        $request = $this->put('/api/transactions/2', $data);
        $request->assertOk();

        $response = $this->get('/api/transactions');
        $response->assertOk()
            ->assertJsonCount(3)
            ->assertJson([
                [
                    'id' => 3,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->startOfMonth()->addDay(30)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                ],
                [
                    'id' => 2,
                    'description' => 'Transaction Update',
                    'amount' => 100,
                    'type' => 'Income',
                    'due_at' => Carbon::now()->startOfMonth()->addDay(14)->toDateString(),
                    'category_id' => 2,
                    'account_id' => 2,
                    'payed' => false,
                    'first_transaction' => 1
                ],
                [
                    'id' => 1,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->startOfMonth()->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                ],
            ]);

        $this->assertDatabaseHas('recurring_transactions', [
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'last_date' => Carbon::now()->startOfMonth()->addDay(30)->toDateString().' 00:00:00',
            'category_id' => 1,
            'account_id' => 1,
            'first_transaction' => 1,
            'period' => 'Biweekly'
        ]);
    }

    /**
     * @test
     */
    public function aUserCanUpdateThisAndNextRecurringTransactionsUpdatingTheRecurringModel()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Category::class, 2)->create();
        factory(BankAccount::class, 2)->create();
        factory(Transaction::class)->create([
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'due_at' => Carbon::now()->startOfMonth(),
            'category_id' => 1,
            'account_id' => 1,
            'payed' => false,
            'first_transaction' => 1,
            'is_recurring' => true,
        ]);
        factory(Transaction::class)->create([
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'due_at' => Carbon::now()->startOfMonth()->addDay(14),
            'category_id' => 1,
            'account_id' => 1,
            'payed' => false,
            'first_transaction' => 1,
            'is_recurring' => true,
        ]);
        factory(Transaction::class)->create([
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'due_at' => Carbon::now()->startOfMonth()->addDay(30),
            'category_id' => 1,
            'account_id' => 1,
            'payed' => false,
            'first_transaction' => 1,
            'is_recurring' => true,
        ]);
        factory(RecurringTransaction::class)->create([
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'last_date' => Carbon::now()->startOfMonth()->addDay(30),
            'category_id' => 1,
            'account_id' => 1,
            'first_transaction' => 1,
            'period' => 'Biweekly'
        ]);

        $response = $this->get('/api/transactions');
        $response->assertOk()
            ->assertJsonCount(3)
            ->assertJson([
                [
                    'id' => 3,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->startOfMonth()->addDay(30)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                ],
                [
                    'id' => 2,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->startOfMonth()->addDay(14)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                ],
                [
                    'id' => 1,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->startOfMonth()->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                ],
            ]);

        $this->assertDatabaseHas('recurring_transactions', [
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'last_date' => Carbon::now()->startOfMonth()->addDay(30)->toDateString().' 00:00:00',
            'category_id' => 1,
            'account_id' => 1,
            'first_transaction' => 1,
            'period' => 'Biweekly'
        ]);

        $data = [
            'description' => 'Transaction Update',
            'amount' => 100,
            'type' => 'Income',
            'category_id' => 2,
            'account_id' => 2,
            'repeatCount' => 'next'
        ];

        $request = $this->put('/api/transactions/2', $data);
        $request->assertOk();

        $response = $this->get('/api/transactions');
        $response->assertOk()
            ->assertJsonCount(3)
            ->assertJson([
                [
                    'id' => 3,
                    'description' => 'Transaction Update',
                    'amount' => 100,
                    'type' => 'Income',
                    'due_at' => Carbon::now()->startOfMonth()->addDay(30)->toDateString(),
                    'category_id' => 2,
                    'account_id' => 2,
                    'payed' => false,
                    'first_transaction' => 1
                ],
                [
                    'id' => 2,
                    'description' => 'Transaction Update',
                    'amount' => 100,
                    'type' => 'Income',
                    'due_at' => Carbon::now()->startOfMonth()->addDay(14)->toDateString(),
                    'category_id' => 2,
                    'account_id' => 2,
                    'payed' => false,
                    'first_transaction' => 1
                ],
                [
                    'id' => 1,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->startOfMonth()->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                ],
            ]);

        $this->assertDatabaseHas('recurring_transactions', [
            'description' => 'Transaction Update',
            'amount' => 100,
            'type' => 'Income',
            'last_date' => Carbon::now()->startOfMonth()->addDay(30)->toDateString().' 00:00:00',
            'category_id' => 2,
            'account_id' => 2,
            'first_transaction' => 1,
            'period' => 'Biweekly'
        ]);
    }

    /**
     * @test
     */
    public function aUserCanUpdateAllRecurringTransactionsUpdatingTheRecurringModel()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Category::class, 2)->create();
        factory(BankAccount::class, 2)->create();
        factory(Transaction::class)->create([
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'due_at' => Carbon::now()->startOfMonth(),
            'category_id' => 1,
            'account_id' => 1,
            'payed' => false,
            'first_transaction' => 1,
            'is_recurring' => true,
        ]);
        factory(Transaction::class)->create([
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'due_at' => Carbon::now()->startOfMonth()->addDay(14),
            'category_id' => 1,
            'account_id' => 1,
            'payed' => false,
            'first_transaction' => 1,
            'is_recurring' => true,
        ]);
        factory(Transaction::class)->create([
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'due_at' => Carbon::now()->startOfMonth()->addDay(30),
            'category_id' => 1,
            'account_id' => 1,
            'payed' => false,
            'first_transaction' => 1,
            'is_recurring' => true,
        ]);
        factory(RecurringTransaction::class)->create([
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'last_date' => Carbon::now()->startOfMonth()->addDay(30),
            'category_id' => 1,
            'account_id' => 1,
            'first_transaction' => 1,
            'period' => 'Biweekly'
        ]);

        $response = $this->get('/api/transactions');
        $response->assertOk()
            ->assertJsonCount(3)
            ->assertJson([
                [
                    'id' => 3,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->startOfMonth()->addDay(30)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                ],
                [
                    'id' => 2,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->startOfMonth()->addDay(14)->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                ],
                [
                    'id' => 1,
                    'description' => 'Transaction',
                    'amount' => 50,
                    'type' => 'Expense',
                    'due_at' => Carbon::now()->startOfMonth()->toDateString(),
                    'category_id' => 1,
                    'account_id' => 1,
                    'payed' => false,
                ],
            ]);

        $this->assertDatabaseHas('recurring_transactions', [
            'description' => 'Transaction',
            'amount' => 50,
            'type' => 'Expense',
            'last_date' => Carbon::now()->startOfMonth()->addDay(30)->toDateString().' 00:00:00',
            'category_id' => 1,
            'account_id' => 1,
            'first_transaction' => 1,
            'period' => 'Biweekly'
        ]);

        $data = [
            'description' => 'Transaction Update',
            'amount' => 100,
            'type' => 'Income',
            'category_id' => 2,
            'account_id' => 2,
            'repeatCount' => 'all'
        ];

        $request = $this->put('/api/transactions/2', $data);
        $request->assertOk();

        $response = $this->get('/api/transactions');
        $response->assertOk()
            ->assertJsonCount(3)
            ->assertJson([
                [
                    'id' => 3,
                    'description' => 'Transaction Update',
                    'amount' => 100,
                    'type' => 'Income',
                    'due_at' => Carbon::now()->startOfMonth()->addDay(30)->toDateString(),
                    'category_id' => 2,
                    'account_id' => 2,
                    'payed' => false,
                    'first_transaction' => 1
                ],
                [
                    'id' => 2,
                    'description' => 'Transaction Update',
                    'amount' => 100,
                    'type' => 'Income',
                    'due_at' => Carbon::now()->startOfMonth()->addDay(14)->toDateString(),
                    'category_id' => 2,
                    'account_id' => 2,
                    'payed' => false,
                    'first_transaction' => 1
                ],
                [
                    'id' => 1,
                    'description' => 'Transaction Update',
                    'amount' => 100,
                    'type' => 'Income',
                    'due_at' => Carbon::now()->startOfMonth()->toDateString(),
                    'category_id' => 2,
                    'account_id' => 2,
                    'payed' => false,
                    'first_transaction' => 1
                ],
            ]);

        $this->assertDatabaseHas('recurring_transactions', [
            'description' => 'Transaction Update',
            'amount' => 100,
            'type' => 'Income',
            'last_date' => Carbon::now()->startOfMonth()->addDay(30)->toDateString().' 00:00:00',
            'category_id' => 2,
            'account_id' => 2,
            'first_transaction' => 1,
            'period' => 'Biweekly'
        ]);
    }
}
