<?php

declare(strict_types = 1);

namespace Plattry\Orm;

use PDO;
use Plattry\Orm\Query\BuilderInterface;
use Plattry\Orm\Query\Pgsql\Builder;

/**
 * A connection instance.
 */
class Connection implements ConnectionInterface
{
    /**
     * The pdo dsn.
     * @var string
     */
    protected string $dsn;

    /**
     * The database username.
     * @var string|null
     */
    protected string|null $username;

    /**
     * The database password.
     * @var string|null
     */
    protected string|null $password;

    /**
     * The pdo options.
     * @var array
     */
    protected array $options = [
        PDO::ATTR_CASE              => PDO::CASE_NATURAL,
        PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS      => PDO::NULL_NATURAL,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES  => false,
    ];

    /**
     * The pdo instance.
     * @var PDO
     */
    protected PDO $pdo;

    /**
     * The query class.
     * @var string
     */
    protected string $queryClass;

    /**
     * The constructor.
     * @param string $dsn
     * @param string|null $username
     * @param string|null $password
     * @param array $options
     */
    public function __construct(
        string $dsn,
        string $username = null,
        string $password = null,
        array $options = []
    ) {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;
        $this->options = array_merge($this->options, $options);
        $this->pdo = new PDO($this->dsn, $this->username, $this->password, $this->options);

        $driver = strstr($dsn, ":", true);
        $this->queryClass = match($driver) {
            "pgsql" => Builder::class
        };
    }

    /**
     * @inheritDoc
     */
    public function execute(string $sql, array $bindings = []): Result
    {
        $statement = $this->pdo->prepare($sql);

        foreach ($bindings as $key => $val) {
            $type = match (get_debug_type($val)) {
                'null' => PDO::PARAM_NULL,
                'bool' => PDO::PARAM_BOOL,
                'int', 'float' => PDO::PARAM_INT,
                'string' => PDO::PARAM_STR
            };
            $statement->bindValue(is_numeric($key) ? $key + 1 : $key, $val, $type);
        }

        $statement->execute();

        return new Result(
            $statement->rowCount(),
            $statement->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    /**
     * @inheritDoc
     */
    public function isInTransaction(): bool
    {
        return $this->pdo->inTransaction();
    }

    /**
     * @inheritDoc
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * @inheritDoc
     */
    public function rollbackTransaction(): bool
    {
        return $this->pdo->rollBack();
    }

    /**
     * @inheritDoc
     */
    public function commitTransaction(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * @inheritDoc
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * @inheritDoc
     */
    public function createQuery(): BuilderInterface
    {
        return new $this->queryClass($this);
    }
}
