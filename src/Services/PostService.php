<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Post;
use App\Helpers\Repository;
use App\Repository\PostRepository;

class PostService
{
    private PostRepository $repository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getPosts(): array
    {
        return $this->repository->findAll();
    }

    public function getPaginatedUsers(int $page, int $limit): array
    {
        return Repository::findPaginatedObject(Post::class, $page, $limit);
    }

    public function getPost(int $id): array
    {
        return $this->repository->findOneBy(['id' => $id]);
    }
}
