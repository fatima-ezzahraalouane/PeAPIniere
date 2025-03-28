<?php

namespace App\DTO;

class RegisterDTO
{
    public string $name;
    public string $email;
    public string $password;
    public ?string $role;

    public function __construct(array $data)
    {
        $this->name     = $data['name'];
        $this->email    = $data['email'];
        $this->password = $data['password'];
        $this->role     = $data['role'] ?? 'client';
    }
}
