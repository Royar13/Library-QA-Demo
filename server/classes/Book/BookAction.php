<?php

class BookAction extends Action {

    public function create() {
        $query = "INSERT INTO books_actions(userId, bookId, description) VALUES(:userId, :id, :description)";
        $this->createByQuery($query);
    }

    public function readAll() {
        $query = "select books_actions.*, users.id, users.name as nameOfUser, users.username, books.name as bookName"
                . " from books_actions"
                . " join users on books_actions.userId=users.id"
                . " left join books on books_actions.bookId=books.id"
                . " where bookId=:bookId";
        $bind[":bookId"] = $this->id;
        $result = $this->db->preparedQuery($query, $bind);
        return $result;
    }

    public function formatDescription($description, $username, $bookName) {
        $description = str_replace("{user}", "\"$username\"", $description);
        $description = str_replace("{book}", "\"$bookName\"", $description);
        return $description;
    }

}
