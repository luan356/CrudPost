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
        $auth = $request->getHeaderLine('Authorization');

        if (!preg_match('/Bearer\s(\S+)/', $auth, $matches)) {
            return $this->unauthorized();
        }

        try {
            $decoded = JWT::decode(
                $matches[1],
                new Key($_ENV['JWT_SECRET'], 'HS256')
            );

            $request = $request->withAttribute('user_id', $decoded->sub);
            return $handler->handle($request);

        } catch (\Throwable $e) {
            return $this->unauthorized();
        }
    }

    private function unauthorized()
    {
        $res = new Response();
        $res->getBody()->write(json_encode(['error' => 'Unauthorized']));
        return $res->withStatus(401)->withHeader('Content-Type', 'application/json');
    }
}
