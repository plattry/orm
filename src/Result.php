<?php

declare(strict_types = 1);

namespace Plattry\Orm;

use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;

/**
 * A result instance.
 */
class Result implements Countable, IteratorAggregate
{
    /**
     * The number of rows affected.
     * @var integer
     */
    protected int $rowCount;

    /**
     * The record rows.
     * @var Record[]
     */
    protected array $rows;

    /**
     * The data model.
     * @var string
     */
    protected string $class;

    /**
     * The constructor.
     * @param integer $rowCount
     * @param array $rows
     */
    public function __construct(int $rowCount, array $rows)
    {
        $this->rowCount = $rowCount;
        $this->rows = $rows;
        $this->class = Record::class;
    }

    /**
     * Set a data model.
     * @param string $class
     * @return static
     */
    public function withClass(string $class): static
    {
        !class_exists($class) &&
        throw new InvalidArgumentException("Not found data model $class.");

        $this->class = $class;

        return $this;
    }

    /**
     * Fetch a record.
     * @return Record|null
     */
    public function fetchOne(): Record|null
    {
        $row = current($this->rows);
        if ($row === false)
            return null;

        next($this->rows);

        return new $this->class($row);
    }

    /**
     * Fetch all records.
     * @return Record[]
     */
    public function fetchAll(): array
    {
        return array_map(fn($row) => new $this->class($row), $this->rows);
    }

    /**
     * Rewind the records point.
     * @return void
     */
    public function rewind(): void
    {
        reset($this->rows);
    }

    /**
     * Convert object to array.
     * @return array
     */
    public function toArray(): array
    {
        return $this->rows;
    }

    /**
     * Get the number of rows affected.
     * @return int
     */
    public function count(): int
    {
        return $this->rowCount;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->fetchAll());
    }
}
