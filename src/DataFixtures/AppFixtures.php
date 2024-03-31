<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;

class AppFixtures
{
    private UserFixtures $userFixtures;
    private PostFixtures $postFixtures;

    public function __construct()
    {
        $this->userFixtures = new UserFixtures();
        $this->postFixtures = new PostFixtures();
    }

    public function load(ObjectManager $manager): void
    {
        $this->userFixtures->load($manager);
        $this->postFixtures->load($manager);
    }
}
