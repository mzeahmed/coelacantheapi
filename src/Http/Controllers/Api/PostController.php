<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Helpers\JSON;
use App\Services\PostService;
use App\Core\Http\Message\Request;
use App\Core\Serializer\Serializer;
use App\Core\Abstracts\AbstractController;

class PostController extends AbstractController
{
    private PostService $service;
    private Serializer $serializer;

    public function __construct(PostService $postService, Serializer $serializer)
    {
        $this->service = $postService;
        $this->serializer = $serializer;
    }

    public function index(Request $request): void
    {
        $page = (int) $request->getAttribute('page');
        $posts = $this->service->getPaginatedUsers($page, 7);

        $serializedPosts = $this->serializer->serialize($posts);

        if (empty($posts)) {
            JSON::sendError(['message' => 'No posts found !'], 404);
        }

        JSON::sendSuccess($serializedPosts);
    }
}
