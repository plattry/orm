<?php

declare(strict_types = 1);

namespace Plattry\Orm\Query;

/**
 * Describe a grammar parser instance.
 */
interface GrammarInterface
{
    /**
     * The sort type asc.
     */
    public const ASC = "ASC";

    /**
     * The sort type desc.
     */
    public const DESC = "DESC";

    /**
     * The connector type and.
     */
    public const AND = "AND";

    /**
     * The connector type or.
     */
    public const OR = "OR";

    /**
     * The join type left.
     */
    public const LEFT = "LEFT";

    /**
     * The join type right.
     */
    public const RIGHT = "RIGHT";

    /**
     * The join type inner.
     */
    public const INNER = "INNER";

    /**
     * The operator type greater than.
     */
    public const GT = ">";

    /**
     * The operator type greater than or equal to.
     */
    public const GTE = ">=";

    /**
     * The operator type equal to.
     */
    public const EQ = "=";

    /**
     * The operator type not equal to.
     */
    public const NE = "!=";

    /**
     * The operator type less than or equal to.
     */
    public const LTE = "<=";

    /**
     * The operator type less than.
     */
    public const LT = "<";

    /**
     * The operator type like.
     */
    public const LIKE = "LIKE";

    /**
     * The operator type in.
     */
    public const IN = "IN";

    /**
     * The operator type constraint.
     */
    public const CONSTRAINT = "CONSTRAINT";

    /**
     * Get a raw statement.
     * @param string $raw
     * @return RawInterface
     */
    public static function raw(string $raw): RawInterface;

    /**
     * Get a insert statement.
     * @param string $insert
     * @return string
     */
    public static function insert(string $insert): string;

    /**
     * Get a delete statement.
     * @param array $delete
     * @return string
     */
    public static function delete(array $delete): string;

    /**
     * Get a update statement.
     * @param array $update
     * @return string
     */
    public static function update(array $update): string;

    /**
     * Get a select statement.
     * @param array $fields
     * @return string
     */
    public static function select(array $fields): string;

    /**
     * Get a values statement.
     * @param array $values
     * @return array
     */
    public static function values(array $values): array;

    /**
     * Get a conflict statement.
     * @param array $target
     * @return string
     */
    public static function conflict(array $target): string;

    /**
     * Get a do statement.
     * @param bool $action
     * @return string
     */
    public static function do(bool $action): string;

    /**
     * Get a using statement.
     * @param array $using
     * @return string
     */
    public static function using(array $using): string;

    /**
     * Get a set statement.
     * @param array $set
     * @return array
     */
    public static function set(array $set): array;

    /**
     * Get a from statement.
     * @param array $from
     * @return string
     */
    public static function from(array $from): string;

    /**
     * Get a join statement.
     * @param array $join
     * @param boolean $isChildren
     * @return string
     */
    public static function join(array $join, bool $isChildren = false): string;

    /**
     * Get a where statement.
     * @param array $where
     * @param boolean $isChildren
     * @return array
     */
    public static function where(array $where, bool $isChildren = false): array;

    /**
     * Get a groupBy statement.
     * @param array $groupBy
     * @return string
     */
    public static function groupBy(array $groupBy): string;

    /**
     * Get a having statement.
     * @param array $having
     * @param boolean $isChildren
     * @return array
     */
    public static function having(array $having, bool $isChildren = false): array;

    /**
     * Get a orderBy statement.
     * @param array $orderBy
     * @return string
     */
    public static function orderBy(array $orderBy): string;

    /**
     * Get a limit statement.
     * @param integer $limit
     * @return array
     */
    public static function limit(int $limit): array;

    /**
     * Get a offset statement.
     * @param integer $offset
     * @return array
     */
    public static function offset(int $offset): array;

    /**
     * Get a returning statement.
     * @param array $returning
     * @return string
     */
    public static function returning(array $returning): string;
}
