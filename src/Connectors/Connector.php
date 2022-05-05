<?php

namespace Yng\Database\Connectors;

use Yng\Database\Config;
use Yng\Database\Contracts\ConnectorInterface;
use Yng\Database\Contracts\GrammarInterface;
use Yng\Database\Query\Grammar\Grammar;
use Yng\Database\Query\Grammar\MySqlGrammar;
use Yng\Database\Query\Grammar\PgSqlGrammar;
use PDO;

abstract class Connector implements ConnectorInterface
{
    /**
     * @var PDO
     */
    protected PDO $PDO;

    /**
     * 默认配置
     *
     * @var array
     */
    protected array $options = [
        //        PDO::ATTR_CASE         => PDO::CASE_NATURAL,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        //        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
        //        PDO::ATTR_STRINGIFY_FETCHES => false,
        //        PDO::ATTR_EMULATE_PREPARES  => false,
    ];

    /**
     * PDO驱动名
     *
     * @var string
     */
    protected string $driver = '';

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->PDO    = new \PDO($this->getDsn($config),$config->getUser(),$config->getPassword(),$this->getOptions($config));
    }

    /**
     * @param Config $config
     *
     * @return array
     */
    protected function getOptions(Config $config)
    {
        return array_merge($this->options, $config->getOptions());
    }

    /**
     * @param Config $config
     *
     * @return string
     */
    public function getDsn(Config $config): string
    {
        if (empty($dsn = $config->getDsn())) {
            $dsn = sprintf('%s:host=%s;port=%d;dbname=%s;charset=%s',
                $this->driver,
                $config->getHost(),
                $config->getPort(),
                $config->getDatabase(),
                $config->getCharset()
            );
        }
        return $dsn;
    }

    /**
     * @param string $query
     * @param array  $bindings
     *
     * @return false|\PDOStatement
     */
    public function statement(string $query, array $bindings = [])
    {
        $statement = $this->getPdo()->prepare($query);
        $this->bindValue($statement, $bindings);
        return $statement;
    }

    /**
     * @param \PDOStatement $PDOStatement
     * @param array         $bindings
     */
    protected function bindValue(\PDOStatement $PDOStatement, array $bindings)
    {
        foreach ($bindings as $key => $value) {
            $PDOStatement->bindValue(
                is_string($key) ? $key : $key + 1,
                $value,
                is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR
            );
        }
    }

    /**
     * @return \PDO
     */
    public function getPdo(): \PDO
    {
        return $this->PDO;
    }

    /**
     * @param string $query
     * @param array  $bindings
     *
     * @return \PDOStatement
     */
    public function run(string $query, array $bindings = []): \PDOStatement
    {
        try {
            $PDOStatement = $this->statement($query, $bindings);
            $PDOStatement->execute();
            return $PDOStatement;
        } catch (\PDOException $PDOException) {
            throw new \PDOException(
                $PDOException->getMessage() . (isset($query) ? sprintf(' (SQL: %s)', $query) : ''),
                (int)$PDOException->getCode(),
                $PDOException->getPrevious()
            );
        }
    }
}

