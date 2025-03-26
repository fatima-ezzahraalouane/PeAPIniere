<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\AuthRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new AuthRepository();
    }

    public function test_register_creates_user_and_returns_token()
    {
        $request = new Request([
            'name' => 'Fatima',
            'email' => 'fatima@example.com',
            'password' => 'password123',
            'role' => 'client'
        ]);

        $response = $this->repo->register($request);
        $data = $response->getData();

        $this->assertEquals(201, $response->status());
        $this->assertEquals('Fatima', $data->user->name);
        $this->assertNotEmpty($data->token);
        $this->assertDatabaseHas('users', ['email' => 'fatima@example.com']);
    }

    public function test_login_returns_token_for_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        $request = new Request([
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response = $this->repo->login($request);
        $data = $response->getData();

        $this->assertEquals(200, $response->status());
        $this->assertEquals($user->email, $data->user->email);
        $this->assertNotEmpty($data->token);
    }

    public function test_login_fails_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        $request = new Request([
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);

        $response = $this->repo->login($request);
        $this->assertEquals(401, $response->status());
        $this->assertEquals('Email ou mot de passe incorrect', $response->getData()->error);
    }

    public function test_me_returns_authenticated_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->repo->me();

        $this->assertEquals(200, $response->status());
        $this->assertEquals($user->email, $response->getData()->email);
    }
}
