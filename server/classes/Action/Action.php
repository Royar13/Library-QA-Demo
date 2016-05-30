<?php

class Action implements IDatabaseAccess {

    protected $db;
    public $id;
    public $description;
    public $userId;

    public function __construct($db, $id, $userId, $description) {
        $this->db = $db;
        $this->id = $id;
        $this->userId = $userId;
        $this->description = $description;
    }

    public function setDatabase($db) {
        $this->db = $db;
    }

    protected function createByQuery($query) {
        $bind["userId"] = $this->userId;
        $bind["id"] = $this->id;
        $bind["description"] = $this->description;
        $this->db->preparedQuery($query, $bind);
    }

    public function readAllByUser() {
        $query = "SELECT books_actions.description, books_actions.actionDate, books.name FROM books_actions"
                . " JOIN books ON books_actions.bookId=books.id"
                . " WHERE userId=:userId AND (books_actions.bookId IS NULL OR books.name IS NOT NULL)"
                . " UNION"
                . " SELECT readers_actions.description, readers_actions.actionDate, readers.name FROM readers_actions"
                . " LEFT JOIN readers ON readers_actions.readerId=readers.id"
                . " WHERE userId=:userId AND (readers_actions.readerId IS NULL OR readers.name IS NOT NULL)";
        $bind[":userId"] = $this->userId;
        $result = $this->db->preparedQuery($query, $bind);
        return $result;
    }

    public function formatDescription($description, $username, $name) {
        $description = str_replace("{user}", "\"$username\"", $description);
        $description = str_replace("{book}", "\"$name\"", $description);
        $description = str_replace("{reader}", "\"$name\"", $description);

        return $description;
    }

}
