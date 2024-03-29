<?php

declare(strict_types=1);

namespace App\Core\Http\Security;

use App\Entity\Users;
use App\Core\Helpers\JWT;
use App\Core\Helpers\JSON;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;

class Authentication
{
    public static function authenticate(string $username, string $password, EntityManager $manager): array|bool
    {
        $user = $manager->getRepository(Users::class)->findOneBy(['login' => $username]);

        if (!$user) {
            JSON::sendError(['message' => 'User not found'], 404);
        }

        if (password_verify($password, $user->getPassword())) {
            $token = JWT::generateToken($user);

            if ($token) {
                $user->setLastLogin(new \DateTimeImmutable());
                try {
                    $manager->persist($user);
                    $manager->flush();
                } catch (\Exception|ORMException $e) {
                    JSON::sendError(['message' => 'Error updating user => ' . $e->getMessage()], 500);
                }

                return $token;
            }

            JSON::sendError(['message' => 'Error generating token'], 500);
        }

        JSON::sendError(['message' => 'Invalid credentials'], 401);

        return false;
    }
}
