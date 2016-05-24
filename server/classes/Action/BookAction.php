<?php

class BookAction extends Action {

    public $bookId = null;

    public function __construct($bookId, $userId, $description) {
        $this->bookId = $bookId;
        $this->userId = $userId;
        $this->description = $description;
    }

    public function create() {
        $query = "INSERT INTO books_actions(userId, description) VALUES(:userId, :description)";
        $bind["userId"] = $userId;
        $bind["description"] = "המשתמש {user} מחק את הספר '{$this->name}'";
        $this->db->preparedQuery($query, $bind);
    }

    public function read() {
        
    }

}
