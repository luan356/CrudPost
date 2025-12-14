<?php

namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JwtMiddleware
{
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        // Pega o header Authorization
        $authHeader = trim($request->getHeaderLine('Authorization'));

        // Verifica se o header existe e contém Bearer
        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return $this->unauthorized('Token not provided');
        }

        $token = $matches[1];

        try {
            // Decodifica o token
            $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));

            // Injeta o user_id no request
            $request = $request->withAttribute('user_id', $decoded->sub);

            // Passa o request adiante
            return $handler->handle($request);

        } catch (\Firebase\JWT\ExpiredException $e) {
            return $this->unauthorized('Token expired');
        } catch (\Throwable $e) {
            return $this->unauthorized('Invalid token');
        }
    }

    private function unauthorized(string $message = 'Unauthorized'): Response
    {
        $response = new Response();
        $response->getBody()->write(json_encode(['error' => $message]));

        return $response
            ->withStatus(401)
            ->withHeader('Content-Type', 'application/json');
    }
}
