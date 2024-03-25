<?php

namespace App\Core\Abstracts;

use App\Core\Database\Database;
use App\Core\Interfaces\RepositoryInterface;

abstract class AbstractRepository implements RepositoryInterface
{
    /**
     * @var string $tableName The table name of the repository
     */
    protected string $tableName;

    /**
     * @var ?Database $db The database instance
     */
    protected ?Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function find(int $id, array $columns): false|array
    {
        $columnsString = implode(',', $columns);

        $query = "SELECT $columnsString FROM $this->tableName WHERE id = :id";

        return $this->db->fetchOne($query, [':id' => $id]);
    }

    public function findOneBy(array $where, array $columns = ['*'], array $joinArgs = [], string $mainTableAlias = ''): false|array
    {
        $query = $this->prepareSelectQuery($where, $columns, $joinArgs, [], $mainTableAlias, 1);

        return $this->db->fetchOne($query);
    }

    public function findAll(array $orderBy = []): array
    {
        $orderClause = '';
        if (!empty($orderBy)) {
            $orderColumns = array_map(static function ($col, $dir) {
                return "{$col} {$dir}";
            }, array_keys($orderBy), $orderBy);

            $orderClause = 'ORDER BY ' . implode(', ', $orderColumns);
        }

        $query = "SELECT * FROM $this->tableName $orderClause";

        return $this->db->fetchAll($query);
    }

    public function findBy(
        array $where,
        array $columns = ['*'],
        array $joinArgs = [],
        array $orderBy = [],
        string $mainTableAlias = '',
        int $limit = null
    ): array {
        $query = $this->prepareSelectQuery($where, $columns, $joinArgs, $orderBy, $mainTableAlias, $limit);

        return $this->db->fetchAll($query);
    }

    public function create(array $data): int|bool
    {
        return $this->db->insert($this->tableName, $data);
    }

    public function update(array $data, array $where): int|bool
    {
        return $this->db->update($this->tableName, $data, $where);
    }

    public function delete(array $where): bool
    {
        return $this->db->delete($this->tableName, $where);
    }

    public function bulkInsert(array $rows): bool
    {
        if (empty($rows)) {
            return false;
        }

        $bulkData = $this->prepareBulkData($rows);
        $query = "
            INSERT INTO {$this->tableName} (" . implode(',', $bulkData['columns']) . ") 
            VALUES " . implode(',', $bulkData['placeholders']);

        return $this->db->execute($query, $bulkData['values']);
    }

    public function bulkUpdate(string $column, array $rows): bool
    {
        if (empty($rows)) {
            return false;
        }

        $bulkData = $this->prepareBulkData($rows);
        $query = "UPDATE {$this->tableName} SET {$column} = CASE id ";

        $ids = array_column($rows, 'id');
        $placeholders = array_fill(0, count($ids), '?');
        $query .= implode(' ', $placeholders);
        $query .= " END WHERE id IN (" . implode(',', $ids) . ")";

        return $this->db->execute($query, $bulkData['values']);
    }

    public function bulkDelete(string $column, array $values): bool
    {
        if (empty($values)) {
            return false;
        }

        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $query = "DELETE FROM {$this->tableName} WHERE {$column} IN ({$placeholders})";

        return $this->db->execute($query, $values);
    }

    public function startTransaction(): void
    {
        $this->db->startTransaction();
    }

    public function commitTransaction(): void
    {
        $this->db->commitTransaction();
    }

    public function rollbackTransaction(): void
    {
        $this->db->rollbackTransaction();
    }

    /**
     * This method construct columns, placeholders and values for bulk insert from the given rows
     *
     * @param array $rows The records to insert
     *
     * @return array The prepared data for bulk insert
     */
    private function prepareBulkData(array $rows): array
    {
        $firstRow = current($rows);
        $columns = array_keys($firstRow);
        $placeholders = array_fill(
            0,
            count($rows),
            '(' . implode(',', array_fill(0, count($columns), '%s')) . ')'
        );
        $values = [];

        foreach ($rows as $row) {
            foreach ($columns as $column) {
                $values[] = $row[$column];
            }
        }

        return [
            'columns' => $columns,
            'placeholders' => $placeholders,
            'values' => $values,
        ];
    }

    /**
     * Prepare one SELECT query by the given parameters
     *
     * @param array $where Where clause to filter results
     *      // Use the default operator '=' :
     *      $results = $repository->findBy(array('column1' => 'someValue')) ;
     *
     *      // Use with a specific operator :
     *      $where = array(
     *          'status' => array('value' => 'someValue', 'operator' => '='),
     *          'category_id' => array('value' => array(1, 2, 3), 'operator' => 'IN')
     *      );
     * @param array $columns Columns to fetch
     * @param array $joinArgs Array of join arguments
     *    $joinArgs = array(
     *           array(
     *               'selectColumns' =>array('users.name as userName', 'posts.title as postTitle'),
     *               'joinType' => 'LEFT',
     *               'joinTable' => 'posts',
     *               'joinTableAlias' => 'posts'
     *               'joinOn' => 'users.id = posts.user_id',
     *           ),
     *           array(
     *               'selectColumns' => array('comments.content as commentContent'),
     *               'joinType' => 'LEFT',
     *               'joinTable' => 'comments',
     *               'joinTableAlias' => 'comments'
     *               'joinOn' => 'users.id = comments.user_id',
     *           )
     *     );
     * @param array $orderBy The order by clause
     * @param string $mainTableAlias The main table alias
     * @param int|null $limit The limit
     *
     * @return string The prepared SELECT query
     */
    private function prepareSelectQuery(
        array $where,
        array $columns = ['*'],
        array $joinArgs = [],
        array $orderBy = [],
        string $mainTableAlias = '',
        int $limit = null
    ): string {
        $whereClause = $this->prepareWhereClause($where);
        $mainTableWithAlias = !empty($mainTableAlias) ? "{$this->tableName} AS {$mainTableAlias}" : $this->tableName;

        [$joinClauses, $columns] = $this->joinArgsLoop($joinArgs, $columns);

        $joinClause = implode(' ', $joinClauses);

        $orderClause = '';
        if (!empty($orderBy)) {
            $orderColumns = array_map(static function ($col, $dir) {
                return "{$col} {$dir}";
            }, array_keys($orderBy), $orderBy);

            $orderClause = 'ORDER BY ' . implode(', ', $orderColumns);
        }

        $limitClause = '';
        if (null !== $limit) {
            $limitClause = "LIMIT {$limit}";
        }

        $columnsString = implode(',', $columns);

        return "SELECT {$columnsString} FROM {$mainTableWithAlias} {$joinClause} WHERE {$whereClause} {$orderClause} {$limitClause}";
    }

    /**
     * Prepare a WHERE clause for SQL queries based on criteria.
     *
     * The function can accept both simple criteria and criteria with specific operators.
     *
     * @param array $where The WHERE clause.
     *
     * @return string The prepared WHERE clause.
     *
     * @example
     * // Use the default operator '=' :
     * $results = $repository->findBy(array('column1' => 'someValue')) ;
     *
     * // Use with a specific operator :
     * $criteria = array(
     *     'column1' => array('value' => 'someValue', 'operator' => '!='),
     *     'column2' => array('value' => array(1, 2, 3), 'operator' => 'IN')
     * );
     * $results = $repository->findBy($criteria) ;
     */
    private function prepareWhereClause(array $where): string
    {
        $whereClasue = [];
        foreach ($where as $column => $data) {
            // If the data is an array with operator and one value
            if (isset($data['operator'], $data['value']) && is_array($data)) {
                if ($data['operator'] === 'IN') {
                    $inValues = implode(
                        ',',
                        array_map(static function ($value) {
                            return is_string($value) ? "'{$value}'" : (int) $value;
                        }, $data['value'])
                    );
                    $whereClasue[] = "{$column} IN ({$inValues})";
                } else {
                    $value = is_string($data['value']) ? "'{$data['value']}'" : (int) $data['value'];
                    $whereClasue[] = "{$column} {$data['operator']} {$value}";
                }
            } elseif (is_string($data) && 0 === stripos($data, 'EXISTS')) {
                // If the data is a string and starts with 'EXISTS'
                $whereClasue[] = $data;
            } else {
                // If it is just one value, use '=' as the default operator
                $data = is_string($data) ? "'{$data}'" : (int) $data;
                $whereClasue[] = "{$column} = {$data}";
            }
        }

        return implode(' AND ', $whereClasue);
    }

    /**
     * Loop over the join arguments to build the JOIN clause.
     *
     * @param array $joinArgs Join arguments.
     * @param array $columns Columns to select.
     *
     * @return array The JOIN clause and the columns to select.
     * @since 2.2.0
     */
    private function joinArgsLoop(array $joinArgs, array $columns = []): array
    {
        $joinClauses = [];

        foreach ($joinArgs as $join) {
            $joinType = $join['joinType'] ?? 'INNER';
            $joinTable = $join['joinTable'] ?? '';
            $joinTableAlias = $join['joinTableAlias'] ?? '';
            $joinOn = $join['joinOn'] ?? '';
            $joinTableWithAlias = $joinTableAlias ? "{$joinTable} AS {$joinTableAlias}" : $joinTable;

            if (!empty($joinTable) && !empty($joinOn)) {
                $joinClauses[] = "{$joinType} JOIN {$joinTableWithAlias} ON {$joinOn}";
            }

            if (!empty($join['selectColumns'])) {
                $columns = $join['selectColumns'];
            }
        }

        return [$joinClauses, $columns];
    }
}
