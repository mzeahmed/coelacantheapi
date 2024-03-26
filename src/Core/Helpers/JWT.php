<?php

declare(strict_types=1);

namespace App\Core\Helpers;

use Firebase\JWT\Key;
use Firebase\JWT\JWT as FirebaseJWT;

class JWT
{
    private const SUPPORTED_ALGS = [
        'HS256',
        'HS384',
        'HS512',
        'RS256',
        'RS384',
        'RS512',
        'ES256',
        'ES384',
        'ES512',
        'PS256',
        'PS384',
        'PS512',
    ];

    public static function generateToken(array $user): array|bool
    {
        $secretKey = defined('JWT_AUTH_SECRET_KEY') ? JWT_AUTH_SECRET_KEY : false;

        if (!$secretKey) {
            return false;
        }

        $token = [
            'iss' => API_URL, // Issuer of the token
            'iat' => time(), // Time when the token was issued.
            'nbf' => time(), // Time before which the token is not yet valid.
            'exp' => time() + 3600, // Expiration time
            'data' => [
                'user' => [
                    'id' => $user['id'],
                ],
            ],
        ];

        $encodedToken = FirebaseJWT::encode($token, $secretKey, self::getAlgorithm());

        if (!$encodedToken) {
            return false;
        }

        return [
            'token' => $encodedToken,
            'user_id' => $user['id']
        ];
    }

    public static function decodeToken(string $token): \stdClass
    {
        $algorithm = self::getAlgorithm();

        try {
            return FirebaseJWT::decode($token, new Key(JWT_AUTH_SECRET_KEY, $algorithm));
        } catch (\UnexpectedValueException $e) {
            throw new \UnexpectedValueException($e->getMessage());
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    private static function getAlgorithm(): false|string
    {
        $algorithm = 'HS256';

        if (!in_array($algorithm, self::SUPPORTED_ALGS, true)) {
            return false;
        }

        return $algorithm;
    }
}
