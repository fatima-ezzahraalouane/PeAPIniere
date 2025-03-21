<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $auth;

    public function __construct(AuthRepositoryInterface $auth)
    {
        $this->auth = $auth;
    }

    public function register(Request $request)
    {
        return $this->auth->register($request);
    }

    public function login(Request $request)
    {
        return $this->auth->login($request);
    }

    public function me()
    {
        return $this->auth->me();
    }
}
