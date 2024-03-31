<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Faker\Generator;
use App\Entity\User;
use App\Entity\Post;
use Doctrine\Persistence\ObjectManager;

class PostFixtures
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = new Generator();
    }

    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();

        if (!$users) {
            throw new \RuntimeException('No users found. Please run UserFixtures before running PostFixtures.');
        }

        foreach ($this->getPostData() as [$content, $createdAt]) {
            $post = new Post();
            $post->setContent($content);
            $post->setCreatedAt(new \DateTimeImmutable($createdAt));
            $post->setAuthor($users[array_rand($users)]);

            $manager->persist($post);
        }

        $manager->flush();
    }

    private function getPostData(): array
    {
        $posts = [];

        foreach ($this->generatePosts() as $post) {
            $posts[] = [
                $post['content'],
                $post['createdAt']
            ];
        }

        return $posts;
    }

    private function generatePosts(): array
    {
        $posts = [];

        for ($i = 0; $i < 500; $i ++) {
            $posts[] = [
                'content' => $this->faker->sentences(300),
                'createdAt' => $this->faker->dateTimeThisYear()->format('Y-m-d H:i:s')
            ];
        }

        return $posts;
    }
}
