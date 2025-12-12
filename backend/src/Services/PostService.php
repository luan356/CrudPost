<?php

namespace App\Services;

use App\Repositories\PostRepository;

class PostService
{
    public function __construct(private PostRepository $posts) {}

    public function create(array $data, int $userId): void
    {
        $this->posts->create(
            $data['title'],
            $data['content'],
            $userId
        );
    }

    public function list(): array
    {
        return $this->posts->findAll();
    }
}
