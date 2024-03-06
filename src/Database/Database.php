<?php

declare(strict_types=1);

namespace App\Database;

class Database {
    private ?DBConnector $connector = null;

    public function __construct() {
        $this->connector = new DBConnector();
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
     * @param array $data The parameters to bind
     *
     * @return array|null
     */
    public function fetchOne(string $query, array $data = []): ?array {
        if (count($data) > 0) {
            $stmt = $this->getPDO()?->prepare($query);
            $stmt->execute($data);

            return $stmt->fetch();
        }

        return $this->getPDO()?->query($query)->fetch();
    }

    /**
     * Insert a record into the database
     *
     * @param string $tableName The table name
     * @param array $data The data to insert
     *
     * @return int|bool
     */
    public function insert(string $tableName, array $data): int|bool {
        $columns = implode(',', array_keys($data));
        $values = implode(',', array_map(static function ($value) {
            return is_string($value) ? "'{$value}'" : (int) $value;
        }, $data));

        $query = "INSERT INTO $tableName ($columns) VALUES ($values)";

        $stmt = $this->getPDO()?->prepare($query);
        $stmt->execute($data);

        $count = $stmt->rowCount();

        if ($count > 0) {
            return $this->lastInsertId();
        }

        return false;
    }

    /**
     * Update a record in the database
     *
     * @param string $tableName The table name
     * @param array $data The data to update
     * @param array $where The where clause
     *
     * @return int|bool
     */
    public function update(string $tableName, array $data, array $where):int| bool {
        $set = implode(',', array_map(static function ($value, $key) {
            return "{$key} = " . (is_string($value) ? "'{$value}'" : (int) $value);
        }, $data, array_keys($data)));

        $whereClause = implode(' AND ', array_map(static function ($value, $key) {
            return "{$key} = " . (is_string($value) ? "'{$value}'" : (int) $value);
        }, $where, array_keys($where)));

        $query = "UPDATE $tableName SET $set WHERE $whereClause";

        $stmt = $this->getPDO()?->prepare($query);
        $stmt->execute($data);

        $count = $stmt->rowCount();

        if ($count > 0) {
            return $count;
        }

        return false;
    }

    /**
     * Delete a record from the database
     *
     * @param string $tableName The table name
     * @param array $where The where clause
     *
     * @return bool
     */
    public function delete(string$tableName,array$where): bool {
        $whereClause = implode(' AND ', array_map(static function ($value, $key) {
            return "{$key} = " . (is_string($value) ? "'{$value}'" : (int) $value);
        }, $where, array_keys($where)));

        $query = "DELETE FROM $tableName WHERE $whereClause";

        $stmt = $this->getPDO()?->prepare($query);
        $stmt->execute($where);

        $count = $stmt->rowCount();

        return $count > 0;
    }

    /**
     * Get last inserted ID
     *
     * @return int
     */
    public function lastInsertId(): int {
        return (int) $this->getPDO()?->lastInsertId();
    }

    /**
     * Get the PDO instance
     *
     * @return \PDO|null
     */
    private function getPDO(): ?\PDO {
        return $this->connector->getConnection();
    }
}
