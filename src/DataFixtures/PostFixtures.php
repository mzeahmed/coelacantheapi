<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Faker\Generator;
use App\Entity\User;
use App\Entity\Post;
use Doctrine\Persistence\ObjectManager;

class PostFixtures
{
    private ObjectManager $manager;
    private Generator $faker;

    public function __construct(ObjectManager $manager, Generator $faker)
    {
        $this->manager = $manager;
        $this->faker = $faker;
    }

    public function load(): void
    {
        $users = $this->manager->getRepository(User::class)->findAll();

        if (!$users) {
            throw new \RuntimeException('No users found. Please run UserFixtures before running PostFixtures.');
        }

        foreach ($this->getPostData() as [$content, $createdAt]) {
            $post = new Post();
            $post->setContent($content);
            $post->setCreatedAt(new \DateTimeImmutable($createdAt));
            $post->setAuthor($users[array_rand($users)]);

            $this->manager->persist($post);
        }

        $this->manager->flush();
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
                'content' => $this->faker->sentence(300),
                'createdAt' => $this->faker->dateTimeThisYear()->format('Y-m-d H:i:s')
            ];
        }

        return $posts;
    }
}
