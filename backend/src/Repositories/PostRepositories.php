<?php

namespace App\Repositories;

use PDO;

class PostRepositories
{
    public function findAll($pdo): array
    {
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
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function findById($pdo, int $id): ?array
{
    $stmt = $pdo->prepare("
        SELECT 
            posts.id,
            posts.title,
            posts.content,
            posts.author_id,       
            users.name AS author,
            posts.created_at,
            posts.updated_at
        FROM posts
        JOIN users ON users.id = posts.author_id
        WHERE posts.id = :id
    ");
    $stmt->execute(['id' => $id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    return $post ?: null;
}


    public function create($pdo, string $title, string $content, int $authorId): void
    {
        $now = date('Y-m-d H:i:s');
        $stmt = $pdo->prepare("
            INSERT INTO posts (title, content, author_id, created_at, updated_at)
            VALUES (:title, :content, :author_id, :created_at, :updated_at)
        ");
        $stmt->execute([
            'title' => $title,
            'content' => $content,
            'author_id' => $authorId,
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }

    public function update($pdo, int $id, string $title, string $content): void
    {
        $now = date('Y-m-d H:i:s');
        $stmt = $pdo->prepare("
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
            'updated_at' => $now
        ]);
    }

    public function delete($pdo, int $id): void
    {
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
