<?php

declare(strict_types=1);

namespace App\Core\Database\Connector;

class PDOConnector
{
    private ?\PDO $connection = null;

    public function __construct()
    {
        $dbname = DB_NAME;
        $user = DB_USER;
        $password = DB_PASSWORD;
        $host = DB_HOST;
        $port = DB_PORT;

        try {
            $this->connection = new \PDO(
                "mysql:host=$host;port=$port;charset=utf8mb4;dbname=$dbname", $user, $password
            );
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function getConnection(): ?\PDO
    {
        return $this->connection;
    }
}
