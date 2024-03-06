<?php

declare(strict_types=1);

namespace App\Database;

class Database {
    private ?DBConnector $connector = null;

    public function __construct() {
        $this->connector = new DBConnector();
    }

    /**
     * Get the PDO instance
     *
     * @return \PDO|null
     */
    private function getPDO(): ?\PDO {
        return $this->connector->getConnection();
    }

    /**
     * Fetch all records from the database
     *
     * @param string $query The query to execute
     * @param array $params The parameters to bind
     *
     * @return array|null
     */
    public function fetchAll(string $query, array $params = []): ?array {
        if (count($params) > 0) {
            $stmt = $this->getPDO()?->prepare($query);
            $stmt->execute($params);

            return $stmt->fetchAll();
        }

        return $this->getPDO()?->query($query)->fetchAll();
    }

    /**
     * Fetch a single record from the database
     *
     * @param string $query The query to execute
     * @param array $params The parameters to bind
     *
     * @return array|null
     */
    public function fetchOne(string $query, array $params = []): ?array {
        if (count($params) > 0) {
            $stmt = $this->getPDO()?->prepare($query);
            $stmt->execute($params);

            return $stmt->fetch();
        }

        return $this->getPDO()?->query($query)->fetch();
    }

    /**
     * Insert a record into the database
     *
     * @param string $query The query to execute
     * @param array $params The parameters to bind
     *
     * @return bool
     */
    public function insert(string $query, array $params): bool {
        return $this->getPDO()?->prepare($query)->execute($params);
    }

    /**
     * Update a record in the database
     *
     * @param string $query The query to execute
     * @param array $params The parameters to bind
     *
     * @return bool
     */
    public function update(string $query, array $params): bool {
        return $this->getPDO()?->prepare($query)->execute($params);
    }

    /**
     * Delete a record from the database
     *
     * @param string $query The query to execute
     * @param array $params The parameters to bind
     *
     * @return bool
     */
    public function delete(string $query, array $params): bool {
        return $this->getPDO()?->prepare($query)->execute($params);
    }

    /**
     * Execute a raw query
     *
     * @param string $query The query to execute
     *
     * @return bool
     */
    public function execute(string $query): bool {
        return $this->getPDO()?->exec($query) !== false;
    }

    /**
     * Get last inserted ID
     *
     * @return int
     */
    public function lastInsertId(): int {
        return (int) $this->getPDO()?->lastInsertId();
    }
}
