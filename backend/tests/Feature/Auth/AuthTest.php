<?php

namespace Tests\Feature\Auth;

use App\Model\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
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
        //Database refresh at each test so we should reinstall the passport
        Artisan::call('passport:install');

        factory(User::class)->create([
            'name' => 'User Test',
            'email' => 'test@test.com',
            'password' => bcrypt('test')
        ]);

        $users = User::all();
        $this->assertEquals(1, $users->count());

        $data = [
            'email' => 'test@test.com',
            'password' => 'test',
        ];

        $request = $this->post('/api/login', $data);

        $request->assertOk()
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_at',
            ]);
    }

    /**
     * @test
     */
    public function loginFailWrongEmail()
    {
        factory(User::class)->create([
            'name' => 'User Test',
            'email' => 'test@test.com',
            'password' => bcrypt('test')
        ]);

        $users = User::all();
        $this->assertEquals(1, $users->count());

        $data = [
            'email' => 'test2@test.com',
            'password' => 'test',
        ];

        $request = $this->post('/api/login', $data);

        $request->assertUnauthorized();
    }

    /**
     * @test
     */
    public function loginFailWrongPassword()
    {
        factory(User::class)->create([
            'name' => 'User Test',
            'email' => 'test@test.com',
            'password' => bcrypt('test')
        ]);

        $users = User::all();
        $this->assertEquals(1, $users->count());

        $data = [
            'email' => 'test@test.com',
            'password' => 'test2',
        ];

        $request = $this->post('/api/login', $data);

        $request->assertUnauthorized();
    }
}
