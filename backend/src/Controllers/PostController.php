<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;
use App\Repositories\PostRepositories;

class PostController
{
    public function index(ServerRequestInterface $request, Response $response)
    {
        try {
            $repo = new PostRepositories();
            $pdo = $request->getAttribute('db');
            $posts = $repo->findAll($pdo);
            return $this->json($response, $posts, 200);
        } catch (\Throwable $th) {
            return $this->json($response, ['error' => 'Something went wrong', 'message' => $th->getMessage()], 500);
        }
    }

    public function show(ServerRequestInterface $request, Response $response, array $args)
    {
        try {
            $repo = new PostRepositories();
            $pdo = $request->getAttribute('db');
            $post = $repo->findById($pdo, (int)$args['id']);
            if (!$post) {
                return $this->json($response, ['error' => 'Post not found'], 404);
            }
            return $this->json($response, $post, 200);
        } catch (\Throwable $th) {
            return $this->json($response, ['error' => 'Something went wrong', 'message' => $th->getMessage()], 500);
        }
    }

    public function create(ServerRequestInterface $request, Response $response)
    {
        try {
            $data = $request->getParsedBody();
            $repo = new PostRepositories();
            $pdo = $request->getAttribute('db');
            $userId = $request->getAttribute('user_id'); // JWT

            if (empty($data['title']) || empty($data['content'])) {
                return $this->json($response, ['error' => 'Missing fields'], 400);
            }

            $repo->create($pdo, $data['title'], $data['content'], $userId);
            return $this->json($response, ['message' => 'Post created'], 201);
        } catch (\Throwable $th) {
            return $this->json($response, ['error' => 'Something went wrong', 'message' => $th->getMessage()], 500);
        }
    }

    public function update(ServerRequestInterface $request, Response $response, array $args)
    {
        try {
            $data = $request->getParsedBody();
            $repo = new PostRepositories();
            $pdo = $request->getAttribute('db');
            $userId = $request->getAttribute('user_id'); // JWT
            $postId = (int)$args['id'];

            $post = $repo->findById($pdo, $postId);
            if (!$post) {
                return $this->json($response, ['error' => 'Post not found'], 404);
            }

            if ($post['author_id'] !== $userId) {
                return $this->json($response, ['error' => 'Unauthorized'], 403);
            }

            $repo->update($pdo, $postId, $data['title'], $data['content']);
            return $this->json($response, ['message' => 'Post updated'], 200);
        } catch (\Throwable $th) {
            return $this->json($response, ['error' => 'Something went wrong', 'message' => $th->getMessage()], 500);
        }
    }

    public function delete(ServerRequestInterface $request, Response $response, array $args)
    {
        try {
            $repo = new PostRepositories();
            $pdo = $request->getAttribute('db');
            $userId = $request->getAttribute('user_id'); // JWT
            $postId = (int)$args['id'];

            $post = $repo->findById($pdo, $postId);
            if (!$post) {
                return $this->json($response, ['error' => 'Post not found'], 404);
            }

            if ($post['author_id'] !== $userId) {
                return $this->json($response, ['error' => 'Unauthorized'], 403);
            }

            $repo->delete($pdo, $postId);
            return $this->json($response, ['message' => 'Post deleted'], 200);
        } catch (\Throwable $th) {
            return $this->json($response, ['error' => 'Something went wrong', 'message' => $th->getMessage()], 500);
        }
    }

    private function json(Response $response, array $data, int $status = 200)
    {
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }
}
