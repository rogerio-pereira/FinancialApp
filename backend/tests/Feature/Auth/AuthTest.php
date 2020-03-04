<?php

namespace Tests\Feature\Auth;

use App\Model\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function loginSuccess()
    {
        $this->withoutExceptionHandling();
        factory(User::class)->create([
            'name' => 'User Test',
            'email' => 'test@test.com',
            'password' => bcrypt('test')
        ]);

        $users = User::all();
        $this->assertEquals(1, $users->count());

        $data = [
            'email' => 'test@test.com',
            'password' => 'test'
        ];

        $response = $this->post('/api/login', $data);

        $response->assertOk()
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_at',
            ]);
    }
}
