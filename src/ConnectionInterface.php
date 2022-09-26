<?php

declare(strict_types = 1);

namespace Plattry\Orm;

use PDO;
use Plattry\Orm\Query\BuilderInterface;

/**
 * Describe a connection instance.
 */
interface ConnectionInterface
{
    /**
     * Executes a sql statement and return a result object.
     * @param string $sql SQL statement.
     * @param array $bindings Parameters.
     * @return Result
     */
    public function execute(string $sql, array $bindings = []): Result;

    /**
     * Get transaction status, true if is in a transaction, or false.
     * @return bool
     */
    public function isInTransaction(): bool;

    /**
     * Start a transaction.
     * @return bool
     */
    public function beginTransaction(): bool;

    /**
     * Rollback a transaction.
     * @return bool
     */
    public function rollbackTransaction(): bool;

    /**
     * Commits a transaction.
     * @return bool
     */
    public function commitTransaction(): bool;

    /**
     * Get the pdo instance.
     * @return PDO
     */
    public function getPdo(): PDO;

    /**
     * Create a query builder.
     */
    public function createQuery(): BuilderInterface;
}
