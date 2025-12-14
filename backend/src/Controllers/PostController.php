<?php


namespace App\Controllers;

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Firebase\JWT\JWT;
use Psr\Log\LoggerInterface;

class PostController
{

    public function show(ServerRequestInterface $request, Response $response, array $args)
{
    $pdo = $request->getAttribute('db');

    $stmt = $pdo->prepare("
        SELECT 
            posts.id,
            posts.title,
            posts.content,
            users.name AS author,
            posts.created_at,
            posts.updated_at
        FROM posts
        JOIN users ON users.id = posts.author_id
        WHERE posts.id = :id
    ");

    $stmt->execute(['id' => $args['id']]);
    $post = $stmt->fetch(\PDO::FETCH_ASSOC); 
    if (!$post) {
        return $this->json($response, ['error' => 'Post not found'], 404);
    }

    return $this->json($response, $post);
}

    public function index(ServerRequestInterface $request, Response $response)
{
    $pdo = $request->getAttribute('db');

    $stmt = $pdo->query("
        SELECT 
            posts.id,
            posts.title,
            users.name AS author,
            posts.created_at
        FROM posts
        JOIN users ON users.id = posts.author_id
        ORDER BY posts.created_at DESC
    ");


    
$posts = $stmt->fetchAll(\PDO::FETCH_ASSOC);

return $this->json($response, $posts);

}
    public function create(ServerRequestInterface $request, Response $response)
    {
        $data = $request->getParsedBody();
        $pdo = $request->getAttribute('db');
        $userId = $request->getAttribute('user_id'); // Obtém o user_id do token

        if (empty($data['title']) || empty($data['content'])) {
            return $this->json($response, ['error' => 'Missing fields'], 400);
        }

        $stmt = $pdo->prepare("
            INSERT INTO posts (title, content, author_id, created_at, updated_at)
            VALUES (:title, :content, :author_id, :created_at, :updated_at)
        ");

        $stmt->execute([
            'title' => $data['title'],
            'content' => $data['content'],
            'author_id' => $userId,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return $this->json($response, ['message' => 'Post created'], 201);
    }

    public function update(ServerRequestInterface $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $pdo = $request->getAttribute('db');
        $userId = $request->getAttribute('user_id');
        $postId = $args['id'];

        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->execute(['id' => $postId]);
        $post = $stmt->fetch();

        if (!$post) {
            return $this->json($response, ['error' => 'Post not found'], 404);
        }

        if ($post['author_id'] !== $userId) {
            return $this->json($response, ['error' => 'Unauthorized'], 403);
        }

        $stmt = $pdo->prepare("
            UPDATE posts 
            SET title = :title, content = :content, updated_at = :updated_at 
            WHERE id = :id
        ");

        $stmt->execute([
            'title' => $data['title'],
            'content' => $data['content'],
            'updated_at' => date('Y-m-d H:i:s'),
            'id' => $postId
        ]);

        return $this->json($response, ['message' => 'Post updated']);
    }

    public function delete(ServerRequestInterface $request, Response $response, $args)
    {
        $pdo = $request->getAttribute('db');
        $userId = $request->getAttribute('user_id');
        $postId = $args['id'];

        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->execute(['id' => $postId]);
        $post = $stmt->fetch();

        if (!$post) {
            return $this->json($response, ['error' => 'Post not found'], 404);
        }

        if ($post['author_id'] !== $userId) {
            return $this->json($response, ['error' => 'Unauthorized'], 403);
        }

        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
        $stmt->execute(['id' => $postId]);

        return $this->json($response, ['message' => 'Post deleted']);
    }

    private function json(Response $response, array $data, int $status = 200)
    {
        $response->getBody()->write(json_encode($data));
        return $response
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json');
    }
}
