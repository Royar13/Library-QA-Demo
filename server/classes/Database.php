<?php

class Database {

    private $connection;
    private $config;

    public function __construct(array $config) {
        $this->config = $config;
    }

    private function getConnection() {
        if ($this->connection == null) {
            try {
                $this->connection = new PDO("mysql:host={$this->config["servername"]};dbname={$this->config["dbname"]}", $this->config["username"], $this->config["password"]);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->connection->query("SET NAMES 'utf8'");
            } catch (Exception $e) {
                echo "Failed to connect to database: " . $e->getMessage();
            }
        }
        return $this->connection;
    }

    public function query($query) {
        return $this->getConnection()->query($query);
    }

    public function preparedQuery($query, $bind) {
        $stmt = $this->getConnection()->prepare($query);
        foreach ($bind as $key => $val) {
            $stmt->bindParam($key, $bind[$key]);
        }
        $stmt->execute();
        return $stmt;
    }

    public function insert($table, $data) {
        $fields = "";
        $values = "";
        foreach ($data as $field => $value) {
            if ($fields != "") {
                $fields.=", ";
                $values.=", ";
            }
            $fields.=$field;
            $values.=":{$field}";
            $bind[":{$field}"] = $data[$field];
        }
        $query = "insert into {$table}({$fields}) values({$values})";
        $this->preparedQuery($query, $bind);
    }

    public function update($table, $data, $condition) {
        $set = "";
        foreach ($data as $field => $value) {
            if ($set != "") {
                $set.=", ";
            }
            $set.="{$field}=:{$field}";
            $bind[":{$field}"] = $data[$field];
        }
        $where = "";
        foreach ($condition as $field => $value) {
            if ($where != "") {
                $where.=" AND ";
            }
            $where.="{$field}=:where{$field}";
            $bind[":where{$field}"] = $condition[$field];
        }
        $query = "update {$table} set {$set} where {$where}";
        $this->preparedQuery($query, $bind);
    }

    public function delete($table, $condition) {
        $where = "";
        foreach ($condition as $field => $value) {
            if ($where != "") {
                $where.=" AND ";
            }
            $where.="{$field}=:where{$field}";
            $bind[":where{$field}"] = $condition[$field];
        }
        $query = "delete from {$table} where {$where}";
        $this->preparedQuery($query, $bind);
    }

    public function getLastId() {
        return $this->getConnection()->lastInsertId();
    }

    public function beginTransaction() {
        $this->getConnection()->beginTransaction();
    }

    public function commit() {
        $this->getConnection()->commit();
    }

    public function rollback() {
        $this->getConnection()->rollBack();
    }

}
