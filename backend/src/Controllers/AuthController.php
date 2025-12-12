<?php
namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;

class AuthController
{
    public function register(ServerRequestInterface $request, Response $response)
    {
        $data = $request->getParsedBody();
        $pdo = $request->getAttribute('db');

        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            return $this->json($response, ['error' => 'Missing fields'], 400);
        }

        $check = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $check->execute(['email' => $data['email']]);

        if ($check->fetch()) {
            return $this->json($response, ['error' => 'Email already exists'], 409);
        }

        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, password)
            VALUES (:name, :email, :password)
        ");

        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT)
        ]);

        return $this->json($response, ['message' => 'User created'], 201);
    }

    public function login(ServerRequestInterface $request, Response $response)
    {
        $data = $request->getParsedBody();
        $pdo = $request->getAttribute('db');

        if (empty($data['email']) || empty($data['password'])) {
            return $this->json($response, ['error' => 'Missing credentials'], 400);
        }

        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = :email");
        $stmt->execute(['email' => $data['email']]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($data['password'], $user['password'])) {
            return $this->json($response, ['error' => 'Invalid credentials'], 401);
        }

        $payload = [
            'sub' => $user['id'],
            'iat' => time(),
            'exp' => time() + 60*60*24
        ];

        $token = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

        return $this->json($response, ['token' => $token]);
    }

    private function json(Response $response, array $data, int $status = 200)
    {
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
