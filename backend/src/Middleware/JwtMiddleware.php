<?php

namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JwtMiddleware
{
    public function __invoke(ServerRequestInterface $req, RequestHandlerInterface $handler)
    {
        $authHeader = $req->getHeaderLine('Authorization');

        // Verifica se o header Authorization existe e começa com "Bearer "
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return $this->unauthorized('Token not provided');
        }

        $token = substr($authHeader, 7); // Remove "Bearer "

        try {
            // Decodifica o token JWT
            $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));

            // Injeta o user_id no request
            $req = $req->withAttribute('user_id', $decoded->sub);
    var_dump($decoded);
            // Passa o request adiante para o próximo middleware ou rota
            return $handler->handle($req);

        } catch (\Firebase\JWT\ExpiredException $e) {
            return $this->unauthorized('Token expired');
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            return $this->unauthorized('Invalid token signature');
        } catch (\Throwable $e) {
            return $this->unauthorized('Invalid token');
        }
    }

    // Cria a resposta 401 Unauthorized
    private function unauthorized(string $message = 'Unauthorized'): Response
    {
        $response = new Response();
        $response->getBody()->write(json_encode(['error' => $message]));

        return $response
            ->withStatus(401)
            ->withHeader('Content-Type', 'application/json');
    }
}
