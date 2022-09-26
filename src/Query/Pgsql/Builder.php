<?php

declare(strict_types = 1);

namespace Plattry\Orm\Query\Pgsql;

use Plattry\Orm\Query\Builder as BaseBuilder;

/**
 * A pgsql query builder instance.
 */
class Builder extends BaseBuilder
{
    /**
     * The SQL parts supported by insert.
     */
    protected const PART_INSERT = [
        "insert", "values", "conflict", "do", "set", "where", "returning"
    ];

    /**
     * The SQL parts supported by delete.
     */
    protected const PART_DELETE = [
        "delete", "using", "where", "returning"
    ];

    /**
     * The SQL parts supported by update.
     */
    protected const PART_UPDATE = [
        "update", "set", "from", "where", "returning"
    ];

    /**
     * The SQL parts supported by select.
     */
    protected const PART_SELECT = [
        "select", "from", "join", "where", "groupBy", "having", "orderBy", "limit", "offset"
    ];

    /**
     * @inheritDoc
     */
    protected const GRAMMAR = Grammar::class;
}
