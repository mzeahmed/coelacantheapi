<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\User;
use App\Entity\Usermeta;
use App\Core\Security\PasswordHasher;
use Doctrine\Persistence\ObjectManager;

class appFixtures
{
    private PasswordHasher $hasher;
    private Generator $faker;

    public function __construct()
    {
        $this->hasher = new PasswordHasher();
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
    }

    private function loadUsers(ObjectManager $manager): void
    {
        foreach ($this->getUserData() as [$email, $password, $twoFaToken]) {
            $firstname = $this->faker->firstName;
            $lastname = $this->faker->lastName;

            $login = strtolower($firstname) . strtolower($lastname);

            $user = new User();
            $user->setLogin($login);
            $user->setEmail($email);
            $user->setPassword($this->hasher->hash($password));
            $user->setTwoFaToken($twoFaToken);
            $user->setCreatedAt(new \DateTimeImmutable());

            $usermeta = new Usermeta();
            $usermeta
                ->setUser($user)
                ?->setMetaKey('firstname')
                ->setMetaValue($firstname);
            $user->addUsermeta($usermeta);

            $usermeta = new Usermeta();
            $usermeta
                ->setUser($user)
                ?->setMetaKey('lastname')
                ->setMetaValue($lastname);
            $user->addUsermeta($usermeta);

            $usermeta = new Usermeta();
            $usermeta
                ->setUser($user)
                ?->setMetaKey('birthdate')
                ->setMetaValue($this->faker->date('Y-m-d H:i:s'));
            $user->addUsermeta($usermeta);

            $manager->persist($user);
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        $users = [];

        foreach ($this->generateUsers() as $user) {
            $users[] = [
                $user['email'],
                $user['password'],
                $user['twoFaToken'],
            ];
        }

        return $users;
    }

    private function generateUsers(): array
    {
        for ($i = 0; $i < 500; $i ++) {
            $users[] = [
                'email' => $this->faker->unique()->email,
                'password' => 'password',
                'twoFaToken' => $this->faker->uuid,
            ];
        }

        return $users;
    }
}
