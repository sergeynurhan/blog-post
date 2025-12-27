<?php

class Connection
{
    public string $servername;
    public string $username;
    public ?string $password;
    public ?string $db;

    public ?mysqli $connection = null;

    public function __construct(
        string $servername,
        string $username,
        ?string $password = null,
        ?string $db = null
    ) {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->db = $db;
    }

    /**
     * @return self
     */
    public function connect(): self
    {
        $connection = new mysqli(
            $this->servername,
            $this->username,
            $this->password,
            $this->db
        );

        if ($connection->connect_error) {
            die("Connection error " . $connection->connect_error);
        }

        $this->connection = $connection;
        return $this;
    }

    /**
     * @return self
     */
    public function createDatabase(): self
    {
        if ($this->db) {
            $query = "CREATE DATABASE IF NOT EXISTS `{$this->db}`";
            if (!$this->connection->query($query)) {
                die('Error creating database: ' . $this->connection->error);
            }
        }

        return $this;
    }

    /**
     * @param string $name
     * @param array $columns
     * @return self
     */
    public function createTable(string $name, array $columns): self
    {
        $queryArr = array();
        foreach ($columns as $key => $value) {
            $queryArr[] = $key . ' ' . $value;
        }

        $result = implode(",", $queryArr);
        $query = "CREATE TABLE IF NOT EXISTS `{$name}`" . " (" . $result . ");";
        if (!$this->connection->query($query)) {
            die('Error creating table ' . $name . ': ' . $this->connection->error);
        }

        return $this;
    }

    /**
     * @param string $table
     * @param array $values
     * @return self
     */
    public function insert(string $table, array $values): self
    {
        $columns = array();
        $insertValues = array();

        foreach ($values as $key => $value) {
            $columns[] = "`" . $key . "`";
            $val = $this->connection->real_escape_string($value);
            $insertValues[] = '"' . $val . '"';
        }

        $keyResult = implode(",", $columns);
        $valueResult = implode(",", $insertValues);

        $query = "INSERT INTO `{$table}`" . " (" . $keyResult . ") " . "VALUES" . " (" . $valueResult . ");";
        if (!$this->connection->query($query)) {
            die('Error inserting into ' . $table . ': ' . $this->connection->error);
        }

        return $this;
    }

    /**
     * @param string $table
     * @return self
     */
    public function dropTable(string $table): self
    {
        $query = "DROP TABLE IF EXISTS `{$table}`";
        $this->connection->query($query);

        return $this;
    }

    /**
     * Helper to run queries
     */
    public function query(string $query)
    {
        return $this->connection->query($query);
    }
}
