<?php

declare(strict_types = 1);

namespace Plattry\Orm;

/**
 * A connection factory instance.
 */
class ConnectionFactory
{
    /**
     * Create a connection.
     * @param array $params Such as driver, host, port, dbname, sslmode, username, password.
     * @param array $options The pdo options.
     * @return ConnectionInterface
     */
    public function create(array $params, array $options = []): ConnectionInterface
    {
        $dsn = match($params["driver"]) {
            "pdo_pgsql" => self::createPgsqlDsn($params)
        };
        
        return new Connection($dsn, $params["username"] ?? null, $params["password"] ?? null, $options);
    }

    /**
     * Create a pdo_pgsql dsn.
     * @param array $params
     * @return string
     */
    protected static function createPgsqlDsn(array $params): string
    {
        $dsn = 'pgsql:';

        if (isset($params['host']) && $params['host'] !== '') {
            $dsn .= "host={$params["host"]};";
        }

        if (isset($params['port']) && $params['port'] !== '') {
            $dsn .= "port={$params["port"]};";
        }

        if (isset($params['dbname']) && $params['dbname'] !== '') {
            $dsn .= "dbname={$params["dbname"]};";
        }

        if (isset($params['sslmode']) && $params['sslmode'] !== '') {
            $dsn .= "sslmode={$params["sslmode"]};";
        }

        return $dsn;
    }
}
