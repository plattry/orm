<?php

declare(strict_types = 1);

namespace Plattry\Orm\Query;

use Plattry\Orm\Result;

/**
 * Describe a query builder instance.
 */
interface BuilderInterface
{
    /**
     * Insert some data in the table.
     * @param string $insert
     * @return static
     */
    public function insert(string $insert): static;

    /**
     * Delete some data in the table.
     * @param string ...$delete
     * @return static
     */
    public function delete(string ...$delete): static;

    /**
     * Update some data in the table.
     * @param string ...$update
     * @return static
     */
    public function update(string ...$update): static;

    /**
     * Select some data in the table.
     * @param string ...$select
     * @return static
     */
    public function select(string ...$select): static;

    /**
     * Set the insert values.
     * @param array $values
     * @param boolean $multi
     * @return static
     */
    public function values(array $values, bool $multi = false): static;

    /**
     * Set the conflict key or fields.
     * @param string ...$target
     * @return static
     */
    public function conflict(string ...$target): static;

    /**
     * Set do update or not on conflict.
     * @param bool $action
     * @return static
     */
    public function do(bool $action = true): static;

    /**
     * Set the using.
     * @param string ...$using
     * @return static
     */
    public function using(string ...$using): static;

    /**
     * Set the data that will update.
     * @param string|array $field
     * @param mixed $value
     * @return static
     */
    public function set(string|array $field, mixed $value = null): static;

    /**
     * Set the tables names.
     * @param string ...$from
     * @return static
     */
    public function from(string ...$from): static;

    /**
     * Set a join parts.
     * @param string $table
     * @param string|array $left
     * @param string|null $operator
     * @param string|null $right
     * @param string $type
     * @return static
     */
    public function join(string $table, string|array $left, string $operator = null, string $right = null, string $type = Grammar::INNER): static;

    /**
     * Set a where parts.
     * @param string|array $left
     * @param string|null $operator
     * @param mixed $right
     * @param string $type
     * @return static
     */
    public function where(string|array $left, string $operator = null, mixed $right = null, string $type = Grammar::AND): static;

    /**
     * Set a groupBy parts.
     * @param string ...$groupBy
     * @return static
     */
    public function groupBy(string ...$groupBy): static;

    /**
     * Set a having parts.
     * @param string|array $left
     * @param string|null $operator
     * @param mixed $right
     * @param string $type
     * @return static
     */
    public function having(string|array $left, string $operator = null, mixed $right = null, string $type = Grammar::AND): static;

    /**
     * Set an orderBy parts.
     * @param string|array $field
     * @param string $direction
     * @return static
     */
    public function orderBy(string|array $field, string $direction = Grammar::ASC): static;

    /**
     * Set a limit rows.
     * @param integer $limit
     * @return static
     */
    public function limit(int $limit): static;

    /**
     * Set an offset rows.
     * @param integer $offset
     * @return static
     */
    public function offset(int $offset): static;

    /**
     * Set the returning fields.
     * @param string ...$returning
     * @return static
     */
    public function returning(string ...$returning): static;

    /**
     * Get the sql and params.
     * @return array
     */
    public function getSql(): array;

    /**
     * Execute the sql.
     * @return Result
     */
    public function execute(): Result;

    /**
     * Reset the sql parts.
     * @return static
     */
    public function reset(): static;
}
