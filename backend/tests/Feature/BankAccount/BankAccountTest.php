<?php

namespace Tests\Feature\BankAccount;

use App\Model\User;
use Tests\TestCase;
use App\Model\BankAccount;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BankAccountTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function aUserCanGetAllBankAccounts()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $bankAccount = factory(BankAccount::class)->create();

        $response = $this->get('/api/bank-accounts');

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([[
                'id' => 1,
                'name' => $bankAccount->name,
                'initialBalance' => $bankAccount->initialBalance
            ]]);
    }

    /**
     * @test
     */
    public function aUserCanCreateABankAccount()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $bankAccount = [
            'name' => 'Bank Account Test', 
            'initialBalance' => 50.78
        ];

        //POST
        $request = $this->post('/api/bank-accounts', $bankAccount);

        $request->assertCreated()
            ->assertJson([
                'id' => 1,
                'name' => 'Bank Account Test',
                'initialBalance' => 50.78
            ]);

        $this->assertDatabaseHas('bank_accounts', $bankAccount);
    }

    /**
     * @test
     */
    public function aUserCanGetABankAccount()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $bankAccount = factory(BankAccount::class)->create();

        $response = $this->get('/api/bank-accounts/1');

        $response->assertOk()
            ->assertJson([
                'id' => 1,
                'name' => $bankAccount->name,
                'initialBalance' => $bankAccount->initialBalance,
            ]);
    }

    /**
     * @test
     */
    public function createBankAccountValidationsFailWithNoName()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $bankAccount = [
            'name' => '', 
            'initialBalance' => 50.78
        ];

        //POST
        $request = $this->post('/api/bank-accounts', $bankAccount);

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
    public function createBankAccountValidationsFailWithStringAsBalance()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $bankAccount = [
            'name' => 'Bank Account Test', 
            'initialBalance' => 'asda'
        ];

        //POST
        $request = $this->post('/api/bank-accounts', $bankAccount);

        $request->assertStatus(422)
        ->assertJson([ 
            'errors' => [
                'initialBalance' => [
                    'The initial balance must be a number.'
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function BankAccountIsCreatedWithoutInitialBalance()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $bankAccount = [
            'name' => 'Bank Account Test', 
        ];

        //POST
        $request = $this->post('/api/bank-accounts', $bankAccount);

        $request->assertCreated()
            ->assertJson([
                'id' => 1,
                'name' => 'Bank Account Test',
            ]);

        $this->assertDatabaseHas('bank_accounts', $bankAccount);

        $bankAccount = BankAccount::find(1);
        $this->assertEquals(0, $bankAccount->initialBalance);
    }

    /**
     * @test
     */
    public function aUserCanUpdateABankAccount()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $bankAccount = factory(BankAccount::class)->create();

        //PUT
        $request = $this->put('/api/bank-accounts/1', ['name' => 'Update Bank Acount', 'initialBalance' => 30]);

        $request->assertOk()
            ->assertJson([
                'id' => 1,
                'name' => 'Update Bank Acount',
                'initialBalance' => 30
            ]);

        $response = $this->get('/api/bank-accounts');

        $response->assertOk()
            ->assertJsonCount(1);
    }

    /**
     * @test
     */
    public function aUserCanDeleteABankAccount()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $bankAccount = factory(BankAccount::class)->create();

        //DELETE
        $request = $this->delete('/api/bank-accounts/1');

        $request->assertOk();

        $response = $this->get('/api/bank-accounts');

        $response->assertOk()
            ->assertJsonCount(0);
    }

    /**
     * @test
     */
    public function BankAccountsCanBeConvertedIdNameArray()
    {
        $this->actingAs(factory(User::class)->create(), 'api');

        factory(BankAccount::class)->create(['name' => 'Account B']);
        factory(BankAccount::class)->create(['name' => 'Account A']);

        //GET
        $response = $this->get('/api/bank-accounts/combobox');


        $response->assertOk()
            ->assertJsonCount(2)
            ->assertJson([
                [
                    'id' => 2,
                    'name' => 'Account A',
                ],
                [
                    'id' => 1,
                    'name' => 'Account B',
                ],
            ]);
    }
}
