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
                $this->connection = mysqli_connect($this->config["servername"], $this->config["username"], $this->config["password"], $this->config["dbname"]);
                mysqli_query($this->connection, "SET NAMES 'utf8'");
            } catch (Exception $e) {
                echo "Failed to connect to database: " . $e->getMessage();
            }
        }
        return $this->connection;
    }

    public function query($query) {
        return mysqli_query($this->getConnection(), $query);
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
            $value = mysqli_real_escape_string($this->getConnection(), $value);
            $values.="'{$value}'";
        }
        mysqli_query($this->getConnection(), "insert into {$table}({$fields}) values({$values})");
    }

    public function update($table, $data, $condition) {
        $set = "";
        foreach ($data as $field => $value) {
            if ($set != "") {
                $set.=", ";
            }
            $value = mysqli_real_escape_string($this->getConnection(), $value);
            $set.="{$field}='{$value}'";
        }
        $where = "";
        foreach ($condition as $field => $value) {
            if ($where != "") {
                $where.=" AND ";
            }
            $value = mysqli_real_escape_string($this->getConnection(), $value);
            $where.="{$field}='{$value}'";
        }
        mysqli_query($this->getConnection(), "update {$table} set {$set} where {$where}");
    }

    public function escape($str) {
        return mysqli_real_escape_string($this->getConnection(), $str);
    }

    public function getLastId() {
        return mysqli_insert_id($this->getConnection());
    }

}
