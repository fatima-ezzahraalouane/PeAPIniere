<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Response;
use Mockery;

class AuthTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function test_register_success()
    {
        $request = new Request([
            'name'     => 'Fatima',
            'email'    => 'fatima@example.com',
            'password' => 'secret123',
            'role'     => 'client'
        ]);

        Hash::shouldReceive('make')
            ->once()
            ->with('secret123')
            ->andReturn('hashed_password');

        $mockUser = new User([
            'name'     => 'Fatima',
            'email'    => 'fatima@example.com',
            'password' => 'hashed_password',
            'role'     => 'client',
        ]);

        // Mock User::create
        $userMock = Mockery::mock('alias:' . User::class);
        $userMock->shouldReceive('create')
            ->once()
            ->with([
                'name'     => 'Fatima',
                'email'    => 'fatima@example.com',
                'password' => 'hashed_password',
                'role'     => 'client',
            ])
            ->andReturn($mockUser);

        // Mock JWTAuth
        JWTAuth::shouldReceive('fromUser')
            ->once()
            ->with($mockUser)
            ->andReturn('mocked_token');

        $repo = new AuthRepository();
        $response = $repo->register($request);

        $response->assertStatus(201)
            ->assertJson([
                'user' => [
                    'name'  => 'Fatima',
                    'email' => 'fatima@example.com',
                    'role'  => 'client',
                ],
                'token' => 'mocked_token'
            ]);
    }

    public function test_login_success()
    {
        $request = new Request([
            'email' => 'fatima@example.com',
            'password' => 'secret123',
        ]);

        $user = new User([
            'name'  => 'Fatima',
            'email' => 'fatima@example.com',
            'role'  => 'client',
        ]);

        Auth::shouldReceive('attempt')
            ->once()
            ->with(['email' => 'fatima@example.com', 'password' => 'secret123'])
            ->andReturn(true);

        Auth::shouldReceive('user')
            ->once()
            ->andReturn($user);

        JWTAuth::shouldReceive('fromUser')
            ->once()
            ->with($user)
            ->andReturn('mocked_token');

        $repo = new AuthRepository();
        $response = $repo->login($request);

        $response->assertStatus(200)
            ->assertJson([
                'user' => [
                    'name'  => 'Fatima',
                    'email' => 'fatima@example.com',
                    'role'  => 'client',
                ],
                'token' => 'mocked_token'
            ]);
    }

    public function test_login_failure()
    {
        $request = new Request([
            'email' => 'wrong@example.com',
            'password' => 'wrongpass',
        ]);

        Auth::shouldReceive('attempt')
            ->once()
            ->with(['email' => 'wrong@example.com', 'password' => 'wrongpass'])
            ->andReturn(false);

        $repo = new AuthRepository();
        $response = $repo->login($request);

        $response->assertStatus(401)
            ->assertJson(['error' => 'Email ou mot de passe incorrect']);
    }

    public function test_me()
    {
        $user = new User([
            'name' => 'Fatima',
            'email' => 'fatima@example.com',
            'role' => 'client'
        ]);

        Auth::shouldReceive('user')
            ->once()
            ->andReturn($user);

        $repo = new AuthRepository();
        $response = $repo->me();

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'Fatima',
                'email' => 'fatima@example.com',
                'role' => 'client'
            ]);
    }
}
