<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Helpers\JSON;
use App\Services\PostService;
use App\Core\Http\Message\Request;
use App\Core\Abstracts\AbstractController;

class PostController extends AbstractController
{
    private PostService $service;

    public function __construct(PostService $postService)
    {
        $this->service = $postService;
    }

    public function index(Request $request): void
    {
        $page = (int) $request->getAttribute('page');
        $posts = $this->service->getPaginatedUsers($page, 7);

        if (empty($posts)) {
            JSON::sendError(['message' => 'No posts found !'], 404);
        }

        $data = [];

        foreach ($posts as $post) {
            $data[] = [
                'id' => $post->getId(),
                'author' => $post->getAuthor(),
                'content' => $post->getContent(),
                'created_at' => $post->getCreatedAt(),
                'updated_at' => $post->getUpdatedAt()
            ];
        }

        JSON::sendSuccess($data);
    }
}
