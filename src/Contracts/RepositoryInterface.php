<?php

namespace App\Contracts;

interface RepositoryInterface {
    /**
     * Detch one record from the database
     *
     * @param int $id The record ID
     * @param array $columns The columns to fetch
     *
     * @return object|null The record or null
     */
    public function find(int $id, array $columns): ?object;

    /**
     * Fetch one record by a given column from the database
     *
     * @param array $where The where clause
     * @param array $columns The columns to fetch
     * @param array $joinArgs The join arguments
     *      $joinArgs = array(
     *            array(
     *                'selectColumns' =>array('users.name as userName', 'posts.title as postTitle'),
     *                'joinType' => 'LEFT',
     *                'joinTable' => 'posts',
     *                'joinTableAlias' => 'posts'
     *                'joinOn' => 'users.id = posts.user_id',
     *            ),
     *            array(
     *                'selectColumns' => array('comments.content as commentContent'),
     *                'joinType' => 'LEFT',
     *                'joinTable' => 'comments',
     *                'joinTableAlias' => 'comments'
     *                'joinOn' => 'users.id = comments.user_id',
     *            )
     *        );
     * @param string $mainTableAlias The main table alias
     *
     * @return object|null The record or null
     */
    public function findOneBy(array $where, array $columns = ['*'], array $joinArgs = [], string $mainTableAlias = ''): ?object;

    /**
     * Fetch all records from the database
     *
     * @param array $orderBy The order by clause
     *
     * @return array The records
     */
    public function findAll(array $orderBy = []): array;

    /**
     * Fetch all records by a given column from the database
     *
     * @param array $where The where clause
     * @param array $columns The columns to fetch
     * @param array $joinArgs The join arguments
     *      $joinArgs = array(
     *             array(
     *                 'selectColumns' =>array('users.name as userName', 'posts.title as postTitle'),
     *                 'joinType' => 'LEFT',
     *                 'joinTable' => 'posts',
     *                 'joinTableAlias' => 'posts'
     *                 'joinOn' => 'users.id = posts.user_id',
     *             ),
     *             array(
     *                 'selectColumns' => array('comments.content as commentContent'),
     *                 'joinType' => 'LEFT',
     *                 'joinTable' => 'comments',
     *                 'joinTableAlias' => 'comments'
     *                 'joinOn' => 'users.id = comments.user_id',
     *             )
     *         );
     * @param array $orderBy The order by clause
     * @param string $mainTableAlias The main table alias
     * @param int|null $limit The limit
     *
     * @return array The records
     */
    public function findBy(
        array $where,
        array $columns = ['*'],
        array $joinArgs = [],
        array $orderBy = [],
        string $mainTableAlias = '',
        int $limit = null
    ): array;

    /**
     * Create a new record in the database
     *
     * @param array $args The record arguments
     *
     * @return int|bool The new record ID or false
     */
    public function create(array $args): int|bool;

    /**
     * Update a record in the database
     *
     * @param array $args The record arguments
     * @param array $where The where clause
     *
     * @return int|bool The number of affected rows or false
     */
    public function update(array $args, array $where): int|bool;

    /**
     * Delete a record from the database
     *
     * @param array $where The where clause
     *
     * @return bool
     */
    public function delete(array $where): bool;
}
