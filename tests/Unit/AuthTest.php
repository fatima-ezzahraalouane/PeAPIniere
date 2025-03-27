<?php

namespace Tests\Unit\Repositories;

use Mockery;
use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    protected $authRepository;
    protected $userMock;
    protected $requestMock;

    public function setUp(): void
    {
        parent::setUp();

        // Create mocks
        $this->userMock = Mockery::mock('alias:App\Models\User');
        $this->requestMock = Mockery::mock(Request::class);

        // Instantiate the repository
        $this->authRepository = new AuthRepository();
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testRegisterCreatesUserAndReturnsToken()
    {
        // Prepare test data
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'role' => 'client'
        ];

        $this->requestMock->shouldReceive('all')
            ->once()
            ->andReturn($userData);

        // Mock request input
        $this->requestMock->shouldReceive('input')
            ->with('name')
            ->andReturn($userData['name']);
        $this->requestMock->shouldReceive('input')
            ->with('email')
            ->andReturn($userData['email']);
        $this->requestMock->shouldReceive('input')
            ->with('password')
            ->andReturn($userData['password']);
        $this->requestMock->shouldReceive('input')
            ->with('role')
            ->andReturn($userData['role']);

        // Mock User creation
        $createdUser = new User($userData);
        $this->userMock->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) use ($userData) {
                return $arg['name'] === $userData['name'] &&
                    $arg['email'] === $userData['email'] &&
                    Hash::check($userData['password'], $arg['password']) &&
                    $arg['role'] === $userData['role'];
            }))
            ->andReturn($createdUser);

        // Mock JWTAuth
        $token = 'mocked-token';
        JWTAuth::shouldReceive('fromUser')
            ->once()
            ->with($createdUser)
            ->andReturn($token);

        // Execute the register method
        $response = $this->authRepository->register($this->requestMock);

        // Assert response
        $this->assertEquals(201, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($userData['name'], $responseData['user']['name']);
        $this->assertEquals($token, $responseData['token']);
    }

    public function testLoginSuccess()
    {
        // Prepare test data
        $credentials = [
            'email' => 'john@example.com',
            'password' => 'password123'
        ];

        // Mock request
        $this->requestMock->shouldReceive('only')
            ->with('email', 'password')
            ->andReturn($credentials);

        Auth::shouldReceive('guard')
            ->andReturnSelf();

        // Mock Auth
        Auth::shouldReceive('attempt')
            ->once()
            ->with($credentials)
            ->andReturn(true);

        $user = new User(['name' => 'John Doe', 'email' => $credentials['email']]);
        Auth::shouldReceive('user')
            ->once()
            ->andReturn($user);

        // Mock JWTAuth
        $token = 'mocked-token';
        JWTAuth::shouldReceive('fromUser')
            ->once()
            ->with($user)
            ->andReturn($token);

        // Execute login
        $response = $this->authRepository->login($this->requestMock);

        // Assert response
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($user->name, $responseData['user']['name']);
        $this->assertEquals($token, $responseData['token']);
    }

    public function testLoginFailure()
    {
        // Prepare test data
        $credentials = [
            'email' => 'john@example.com',
            'password' => 'wrongpassword'
        ];

        // Mock request
        $this->requestMock->shouldReceive('only')
            ->with('email', 'password')
            ->andReturn($credentials);

        // Mock Auth failure
        Auth::shouldReceive('attempt')
            ->once()
            ->with($credentials)
            ->andReturn(false);

        // Execute login
        $response = $this->authRepository->login($this->requestMock);

        // Assert response
        $this->assertEquals(401, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Email ou mot de passe incorrect', $responseData['error']);
    }

    public function testMeReturnsAuthenticatedUser()
    {
        // Mock authenticated user
        $user = new User();
        $user->forceFill(['name' => 'John Doe', 'email' => 'john@example.com']);

        Auth::shouldReceive('user')
            ->once()
            ->andReturn($user);

        // Execute me
        $response = $this->authRepository->me();

        // Assert response
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($user->name, $responseData['name']);
        $this->assertEquals($user->email, $responseData['email']);
    }
}
