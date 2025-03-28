<?php


namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\DTO\RegisterDTO;
use App\DTO\LoginDTO;

class AuthRepository implements AuthRepositoryInterface
{
    public function register(RegisterDTO $data)
    {
        $user = User::create([
            'name'     => $data->name,
            'email'    => $data->email,
            'password' => Hash::make($data->password),
            'role'     => $request->role ?? 'client',
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'user'  => $user,
            'token' => $token
        ], 201);
    }

    public function login(LoginDTO $data)
    {
        $credentials = [
            'email'    => $data->email,
            'password' => $data->password,
        ];
        
        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Email ou mot de passe incorrect'], 401);
        }

        $user = Auth::user();
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'user'  => $user,
            'token' => $token
        ], 200);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
