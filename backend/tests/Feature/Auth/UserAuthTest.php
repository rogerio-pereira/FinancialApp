<?php

namespace Tests\Feature\Auth;

use App\Model\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function noAuthUserShouldReturnNull()
    {
        //get
        $request = $this->get('/api/auth-user');
        $request->assertOk()
            ->assertJsonCount(0);
    }

    /**
     * @test
     *
     * @return void
     */
    public function authUserShouldReturnTheUser()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        //get
        $request = $this->get('/api/auth-user');
        $request->assertOk()
            ->assertJson([
                'name' => $user->name,
                'email' => $user->email,
            ]);
    }
}
