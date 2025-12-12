<?php

namespace App\Repositories;

use PDO;

class PostRepository
{
    public function __construct(private PDO $pdo) {}

    public function create(
        string $title,
        string $content,
        int $authorId
    ): void {
        $stmt = $this->pdo->prepare("
            INSERT INTO posts (title, content, author_id, created_at, updated_at)
            VALUES (:title, :content, :author_id, datetime('now'), datetime('now'))
        ");

        $stmt->execute([
            'title' => $title,
            'content' => $content,
            'author_id' => $authorId
        ]);
    }

    public function findAll(): array
    {
        return $this->pdo
            ->query("
                SELECT p.*, u.name AS author
                FROM posts p
                JOIN users u ON u.id = p.author_id
                ORDER BY p.created_at DESC
            ")
            ->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM posts WHERE id = :id
        ");
        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: null;
    }

    public function update(
        int $id,
        string $title,
        string $content
    ): void {
        $stmt = $this->pdo->prepare("
            UPDATE posts
            SET title = :title,
                content = :content,
                updated_at = datetime('now')
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $id,
            'title' => $title,
            'content' => $content
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM posts WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
    }
}
