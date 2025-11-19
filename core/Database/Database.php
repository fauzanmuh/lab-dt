<?php

namespace Core\Database;

use PDO;
use PDOException;

/**
 * Database Class
 * Handles database connections and queries
 */
class Database
{
    protected ?PDO $connection = null;
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get database connection
     */
    public function getConnection(): PDO
    {
        if ($this->connection === null) {
            $this->connect();
        }

        return $this->connection;
    }

    /**
     * Connect to database
     */
    protected function connect(): void
    {
        $driver = $this->config['driver'] ?? 'pgsql';
        $host = $this->config['host'] ?? 'localhost';
        $port = $this->config['port'] ?? 5432;
        $database = $this->config['database'] ?? '';
        $username = $this->config['username'] ?? 'postgres';
        $password = $this->config['password'] ?? '';
        $charset = $this->config['charset'] ?? 'utf8';

        $dsn = "{$driver}:host={$host};port={$port};dbname={$database};charset={$charset}";

        try {
            $this->connection = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Execute a SELECT query
     */
    public function query(string $sql, array $params = []): array
    {
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll();
    }

    /**
     * Execute an INSERT, UPDATE, or DELETE query
     */
    public function execute(string $sql, array $params = []): int
    {
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute($params);

        return $statement->rowCount();
    }

    /**
     * Get the last inserted ID
     */
    public function lastInsertId(): string
    {
        return $this->getConnection()->lastInsertId();
    }

    /**
     * Begin a transaction
     */
    public function beginTransaction(): void
    {
        $this->getConnection()->beginTransaction();
    }

    /**
     * Commit a transaction
     */
    public function commit(): void
    {
        $this->getConnection()->commit();
    }

    /**
     * Rollback a transaction
     */
    public function rollback(): void
    {
        $this->getConnection()->rollBack();
    }
}
