<?php

declare(strict_types=1);

namespace App\Core\Abstracts;

use Doctrine\ORM\EntityManager;
use App\Core\Http\Message\Request;
use App\Core\Database\Connector\DoctrineConnector;

abstract class AbstractController
{
    protected function getEntityManager(): EntityManager
    {
        return DoctrineConnector::getEntityManager();
    }

    protected function getRequestData(Request $request): array
    {
        $body = $request->getBody();
        $contents = $body->getContents();

        if (empty($contents)) {
            throw new \RuntimeException('Error : The request body is empty');
        }
        
        try {
            $data = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error : ' . $e->getMessage());
        }

        return $data;
    }
}