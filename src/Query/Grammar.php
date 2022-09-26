<?php

declare(strict_types = 1);

namespace Plattry\Orm\Query;

/**
 * A common grammar parser instance.
 */
class Grammar implements GrammarInterface
{
    /**
     * @inheritDoc
     */
    public static function raw(string $raw): RawInterface
    {
        return new Raw($raw);
    }

    /**
     * @inheritDoc
     */
    public static function insert(string $insert): string
    {
        return "INSERT INTO {$insert}";
    }

    /**
     * @inheritDoc
     */
    public static function delete(array $delete): string
    {
        return "DELETE " . implode(", ", $delete);
    }

    /**
     * @inheritDoc
     */
    public static function update(array $update): string
    {
        return "UPDATE " . implode(", ", $update);
    }

    /**
     * @inheritDoc
     */
    public static function select(array $fields): string
    {
        return "SELECT " . implode(", ", $fields);
    }

    /**
     * @inheritDoc
     */
    public static function values(array $values): array
    {
        $statement = $parameter = [];
        
        foreach($values as $item) {
            ksort($item);
            
            $statement[] = "(" . implode(', ', array_fill(0, count($item), "?")) . ")";
            array_push($parameter, ...array_values($item));
        }

        $field = array_keys(current($values));
        sort($field);
        
        return ["(" . implode(", ", $field) . ") VALUES " . implode(", ", $statement), $parameter];
    }

    /**
     * @inheritDoc
     */
    public static function conflict(array $target): string
    {
        if (in_array('', $target, true)) {
            return "ON CONFLICT";
        }

        $index = array_search(static::CONSTRAINT, $target);
        if ($index === false) {
            return "ON CONFLICT (" . implode(", ", $target) . ")";
        }

        return "ON CONFLICT ON CONSTRAINT {$target[$index > 0 ? 0 : 1]}";
    }

    /**
     * @inheritDoc
     */
    public static function do(bool $action): string
    {
        return $action ? "DO UPDATE" : "DO NOTHING";
    }

    /**
     * @inheritDoc
     */
    public static function using(array $using): string
    {
        return "USING " . implode(", ", $using);
    }

    /**
     * @inheritDoc
     */
    public static function set(array $set): array
    {
        $statement = $parameter = [];

        foreach($set as $item) {
            [$field, $value] = $item;
            if ($value instanceof RawInterface) {
                $statement[] = "{$field} = $value";
            } else {
                $statement[] = "{$field} = ?";
                $parameter[] = $value;
            }
        }

        return ["SET " . implode(", ", $statement), $parameter];
    }

    /**
     * @inheritDoc
     */
    public static function from(array $from): string
    {
        return "FROM " . implode(", ", $from);
    }

    /**
     * @inheritDoc
     */
    public static function join(array $join, bool $isChildren = false): string
    {
        $statement = [];

        // Build children statement.
        if ($isChildren) {
            foreach($join as $item) {
                [$left, $operator, $right] = $item;
                $statement[] = "{$left} {$operator} {$right}";
            }

            return implode(" ". Grammar::AND ." ", $statement);
        }

        // Build main statement.
        foreach ($join as $item) {
            [$table, $left, $operator, $right, $type] = $item + [2 => null, 3 => null, 4 => static::INNER];
            
            if (is_array($left)) {
                $statement[] = "{$type} JOIN {$table} ON " . static::join($left, true);
            } elseif (!is_null($operator) && !is_null($right)) {
                $statement[] = "{$type} JOIN {$table} ON {$left} {$operator} {$right}";
            } else {
                $statement[] = "{$type} JOIN {$table} ON {$left}";
            }
        }
        
        return implode(" ", $statement);
    }

    /**
     * @inheritDoc
     */
    public static function where(array $where, bool $isChildren = false): array
    {
        $statement = $parameter = [];

        foreach($where as $index => $item) {
            [$left, $operator, $right, $type] = $item + [1=> null, 2 => null, 3 => static::AND];

            if ($index !== 0) {
                $statement[] = "{$type}";
            }

            if (is_array($left)) {
                [$childStatement, $childParameter] = static::where($left, true);
                $statement[] = "({$childStatement})";
                array_push($parameter, ...$childParameter);
            } elseif (!is_null($operator) && !is_null($right)) {
                if ($operator === static::IN) {
                    $statement[] = "{$left} {$operator} (" . implode(', ', array_fill(0, count($right), "?")) . ")";
                    array_push($parameter, ...$right);
                } else {
                    $statement[] = "{$left} {$operator} ?";
                    $parameter[] = $right;
                }
            } else {
                $statement[] = "{$left}";
            }
        }

        if ($isChildren) {
            return [implode(" ", $statement), $parameter];
        }

        return ["WHERE " . implode(" ", $statement), $parameter];
    }

    /**
     * @inheritDoc
     */
    public static function groupBy(array $groupBy): string
    {
        return "GROUP BY " . implode(", ", $groupBy);
    }

    /**
     * @inheritDoc
     */
    public static function having(array $having, bool $isChildren = false): array
    {
        $statement = $parameter = [];

        foreach($having as $index => $item) {
            [$left, $operator, $right, $type] = $item + [1=> null, 2 => null, 3 => static::AND];

            if ($index !== 0) {
                $statement[] = "{$type}";
            }

            if (is_array($left)) {
                [$childStatement, $childParameter] = static::having($left, true);
                $statement[] = "({$childStatement})";
                array_push($parameter, ...$childParameter);
            } elseif (!is_null($operator) && !is_null($right)) {
                if ($operator === static::IN) {
                    $statement[] = "{$left} {$operator} (" . implode(', ', array_fill(0, count($right), "?")) . ")";
                    array_push($parameter, ...$right);
                } else {
                    $statement[] = "{$left} {$operator} ?";
                    $parameter[] = $right;
                }
            } else {
                $statement[] = "{$left}";
            }
        }

        if ($isChildren) {
            return [implode(" ", $statement), $parameter];
        }

        return ["HAVING " . implode(" ", $statement), $parameter];
    }

    /**
     * @inheritDoc
     */
    public static function orderBy(array $orderBy): string
    {
        $statement = [];

        foreach($orderBy as $item) {
            [$field, $direction] = $item + [1 => static::ASC];
            $statement[] = "{$field} {$direction}";
        }

        return "ORDER BY " . implode(", ", $statement);
    }

    /**
     * @inheritDoc
     */
    public static function limit(int $limit): array
    {
        return ["LIMIT ?", [$limit]];
    }

    /**
     * @inheritDoc
     */
    public static function offset(int $offset): array
    {
        return ["OFFSET ?", [$offset]];
    }

    /**
     * @inheritDoc
     */
    public static function returning(array $returning): string
    {
        return "RETURNING " . implode(", ", $returning);
    }
}
