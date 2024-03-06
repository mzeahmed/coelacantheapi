<?php

declare(strict_types=1);

namespace App\System;

/**
 * Class DatabaseConnector
 *
 * Connector to the database
 *
 * @package App\System
 */
class DatabaseConnector {
    private ?\PDO $dbconnection = null;

    public function __construct() {
        $dbname = getenv('DB_NAME');
        $user = getenv('DB_USER');
        $password = getenv('DB_PASSWORD');
        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT');

        try {
            $this->dbconnection = new \PDO("mysql:host=$host;port=$port;charset=utf8mb4;dbname=$dbname", $user, $password);
            $this->dbconnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    /**
     * @return \PDO|null
     */
    public function getDbconnection(): ?\PDO {
        return $this->dbconnection;
    }
}
