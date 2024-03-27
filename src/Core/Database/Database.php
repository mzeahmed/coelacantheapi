<?php

declare(strict_types=1);

namespace App\Core\Database;

use App\Core\Database\Connector\PDOConnector;

class Database
{
    private ?PDOConnector $connector;

    public function __construct(PDOConnector $connector)
    {
        $this->connector = $connector;
    }

    public function fetchAll(string $query, array $params = []): ?array
    {
        if (count($params) > 0) {
            $stmt = $this->getPDO()?->prepare($query);
            $stmt->execute($params);

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $this->getPDO()?->query($query)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchOne(string $query, array $data = []): false|array
    {
        if (count($data) > 0) {
            $stmt = $this->getPDO()?->prepare($query);
            $stmt->execute($data);

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        return $this->getPDO()?->query($query)->fetch(\PDO::FETCH_ASSOC);
    }

    public function insert(string $tableName, array $data): int|bool
    {
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

    public function update(string $tableName, array $data, array $where): int|bool
    {
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

    public function delete(string $tableName, array $where): bool
    {
        $whereClause = implode(' AND ', array_map(static function ($value, $key) {
            return "{$key} = " . (is_string($value) ? "'{$value}'" : (int) $value);
        }, $where, array_keys($where)));

        $query = "DELETE FROM $tableName WHERE $whereClause";

        $stmt = $this->getPDO()?->prepare($query);
        $stmt->execute($where);

        $count = $stmt->rowCount();

        return $count > 0;
    }

    public function lastInsertId(): int
    {
        return (int) $this->getPDO()?->lastInsertId();
    }

    public function execute(string $query, array $params = []): bool
    {
        $stmt = $this->getPDO()?->prepare($query);
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    public function startTransaction(): void
    {
        $this->getPDO()?->beginTransaction();
    }

    public function commitTransaction(): void
    {
        $this->getPDO()?->commit();
    }

    public function rollbackTransaction(): void
    {
        $this->getPDO()?->rollBack();
    }

    private function getPDO(): ?\PDO
    {
        return $this->connector->getConnection();
    }
}
