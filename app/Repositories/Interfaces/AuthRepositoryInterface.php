<?php 

namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

use App\DTO\RegisterDTO;
use App\DTO\LoginDTO;

interface AuthRepositoryInterface
{
    public function register(RegisterDTO $data);
    public function login(LoginDTO $data);
    public function me();
}
