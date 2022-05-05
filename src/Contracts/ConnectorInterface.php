<?php

namespace Yng\Database\Contracts;

use Yng\Database\Config;

interface ConnectorInterface
{
    /**
     * @param string $query
     * @param array  $bindings
     *
     * @return \PDOStatement | false
     */
    public function statement(string $query, array $bindings);

    /**
     * @return \PDO
     */
    public function getPdo(): \PDO;

    /**
     * @param Config $config
     *
     * @return string
     */
    public function getDsn(Config $config): string;
}
