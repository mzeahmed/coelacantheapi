<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\User;
use App\Helpers\JSON;
use Doctrine\ORM\EntityManager;
use App\Repository\UsersRepository;
use App\Core\Security\PasswordHasher;
use Doctrine\ORM\Exception\ORMException;

class UserService
{
    private UsersRepository $repo;
    private PasswordHasher $hasher;

    public function __construct(UsersRepository $repo, PasswordHasher $hasher)
    {
        $this->repo = $repo;
        $this->hasher = $hasher;
    }

    public function getUsers(): array
    {
        return $this->repo->findAll();
    }

    public function getUser(int $id): ?User
    {
        return $this->repo->findOneBy(['id' => $id]);
    }

    public function createUser(string $login, string $email, string $password, EntityManager $manager): bool|User
    {
        $user = new User();

        $password = $this->hasher->hash($password);

        $user->setLogin($login)
             ->setEmail($email)
             ->setPassword($password);

        try {
            $manager->persist($user);
            $manager->flush();
        } catch (\Exception|ORMException $e) {
            JSON::sendError(['message' => 'Error creating user => ' . $e->getMessage()], 500);
        }

        return $user;
    }

    public function updateUser(int $id, array $data, EntityManager $manager): bool
    {
        $user = $manager->getRepository(User::class)->findOneBy(['id' => $id]);

        if (!$user) {
            JSON::sendError(['message' => 'User not found'], 404);
        }

        foreach ($data as $key => $value) {
            $keysArray = explode('_', $key);

            if (count($keysArray) > 1) {
                $string = '';
                foreach ($keysArray as $k) {
                    $string .= ucfirst($k);
                }
                $method = 'set' . $string;
            } else {
                $method = 'set' . ucfirst($key);
            }

            if (method_exists($user, $method)) {
                $user->$method($value);
            }
        }

        $user->setUpdatedAt(new \DateTimeImmutable());

        try {
            $manager->persist($user);
            $manager->flush();
        } catch (\Exception|ORMException $e) {
            JSON::sendError(['message' => 'Error updating user => ' . $e->getMessage()], 500);
        }

        return true;
    }
}
