<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Firebase\JWT\JWT;

class AuthService
{
    public function __construct(private UserRepository $users) {}

    public function register(array $data): void
    {
        if ($this->users->findByEmail($data['email'])) {
            throw new \DomainException('Email already exists');
        }

        $this->users->create(
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_BCRYPT)
        );
    }

    public function login(array $data): string
    {
        $user = $this->users->findByEmail($data['email']);

        if (
            !$user ||
            !password_verify($data['password'], $user['password'])
        ) {
            throw new \DomainException('Invalid credentials');
        }

        return JWT::encode([
            'sub' => $user['id'],
            'iat' => time(),
            'exp' => time() + 86400
        ], $_ENV['JWT_SECRET'], 'HS256');
    }
}
