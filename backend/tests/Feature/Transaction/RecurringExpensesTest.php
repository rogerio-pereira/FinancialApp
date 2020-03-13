<?php

namespace Tests\Feature\Transaction;

use Carbon\Carbon;
use App\Model\User;
use Tests\TestCase;
use App\Model\Category;
use App\Model\BankAccount;
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
        $this->withoutExceptionHandling();
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
}
