<?php

declare(strict_types = 1);

namespace Plattry\Orm\Query;

/**
 * A common raw sql instance.
 */
class Raw implements RawInterface
{
    /**
     * The raw statement.
     * @var string
     */
    protected string $value;

    /**
     * The constructor.
     * @param string $raw
     */
    public function __construct(string $raw)
    {
        $this->value = $raw;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->value;
    }
}