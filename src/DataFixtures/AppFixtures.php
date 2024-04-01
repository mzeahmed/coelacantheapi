<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Faker\Factory;
use App\Core\Security\PasswordHasher;
use Doctrine\Persistence\ObjectManager;

class AppFixtures
{
    private UserFixtures $userFixtures;
    private PostFixtures $postFixtures;
    private PasswordHasher $hasher;

    public function __construct(ObjectManager $manager)
    {
        $this->hasher = new PasswordHasher();
        $faker = Factory::create();

        $this->userFixtures = new UserFixtures($manager, $faker);
        $this->postFixtures = new PostFixtures($manager, $faker);
    }

    public function load(): void
    {
        $this->userFixtures->load($this->hasher);
        $this->postFixtures->load();
    }
}
