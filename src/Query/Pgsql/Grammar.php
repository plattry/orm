<?php

declare(strict_types = 1);

namespace Plattry\Orm\Query\Pgsql;

use Plattry\Orm\Query\Grammar as BaseGrammar;

/**
 * A pgsql grammar parser instance.
 */
class Grammar extends BaseGrammar
{
    /**
     * @inheritDoc
     */
    public static function delete(array $delete): string
    {
        return "DELETE FROM " . implode(", ", $delete);
    }
}
