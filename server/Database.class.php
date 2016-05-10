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
        foreach ($data as $key => $field) {
            if ($fields != "") {
                $fields.=", ";
                $values.=", ";
            }
            $fields.=$key;
            $values.="'{$field->value}'";
        }
        mysqli_query($this->getConnection(), "insert into {$table}({$fields}) values({$values})");
    }

    public function update($table, $data, $condition) {
        $set = "";
        foreach ($data as $key => $field) {
            if ($set != "") {
                $set.=", ";
            }
            $set.="{$key}='{$field->value}'";
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

}

?>