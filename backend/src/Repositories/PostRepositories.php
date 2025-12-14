<?php

namespace App\Repositories;

use PDO;

class PostRepository
{
    public function __construct(private PDO $pdo) {}

    public function create(string $title, string $content, int $authorId): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO posts (title, content, author_id, created_at, updated_at)
            VALUES (:title, :content, :author_id, :created_at, :updated_at)
        ");

        $stmt->execute([
            'title' => $title,
            'content' => $content,
            'author_id' => $authorId,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT 
                posts.id,
                posts.title,
                users.name AS author,
                posts.created_at
            FROM posts
            JOIN users ON users.id = posts.author_id
            ORDER BY posts.created_at DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                posts.id,
                posts.title,
                posts.content,
                users.name AS author,
                posts.created_at,
                posts.updated_at,
                posts.author_id
            FROM posts
            JOIN users ON users.id = posts.author_id
            WHERE posts.id = :id
        ");

        $stmt->execute(['id' => $id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        return $post ?: null;
    }

    public function update(int $id, string $title, string $content): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE posts
            SET title = :title,
                content = :content,
                updated_at = :updated_at
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $id,
            'title' => $title,
            'content' => $content,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM posts WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
