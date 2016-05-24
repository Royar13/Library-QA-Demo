<?php

class Book implements IDatabaseAccess {

    private $db;
    public $id;
    public $name;
    public $sectionId;
    public $bookcaseId;
    public $authorId;
    public $author;
    public $publisherId;
    public $publisher;
    public $releaseYear;
    public $copies;

    public function setDatabase($db) {
        $this->db = $db;
    }

    public function create(BookValidator $validator, $userId) {
        if (!$validator->validate($this))
            return false;

        try {
            $this->resolveAuthor();
            $this->resolvePublisher();

            $fields = $this->toArray();
            $this->db->insert("books", $fields);
            $this->id = $this->db->getLastId();

            $actionFields["userId"] = $userId;
            $actionFields["bookId"] = $this->id;
            $actionFields["description"] = "המשתמש {user} יצר את הספר {book}";
            $this->db->insert("books_actions", $actionFields);
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    private function toArray() {
        $fields["id"] = $this->id;
        $fields["name"] = $this->name;
        $fields["sectionId"] = $this->sectionId;
        $fields["bookcaseId"] = $this->bookcaseId;
        $fields["authorId"] = $this->authorId;
        $fields["publisherId"] = $this->publisherId;
        $fields["releaseYear"] = $this->releaseYear;

        if (empty($fields["releaseYear"]))
            $fields["releaseYear"] = null;

        $fields["copies"] = $this->copies;
        return $fields;
    }

    public function update(BookValidator $validator, $userId) {
        if (!$validator->validateUpdate($this))
            return false;

        try {
            $this->resolveAuthor();
            $this->resolvePublisher();

            $fields = $this->toArray();
            $condition["id"] = $this->id;
            $this->db->update("books", $fields, $condition);

            $actionFields["userId"] = $userId;
            $actionFields["bookId"] = $this->id;
            $actionFields["description"] = "המשתמש {user} עדכן את הספר {book}";
            $this->db->insert("books_actions", $actionFields);
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function delete(BookValidator $validator, $userId) {
        if (!$validator->validateDelete($this))
            return false;

        try {
            $condition["id"] = $this->id;
            $this->db->delete("books", $condition);

            $action=new BookAction();
            
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    private function resolveAuthor() {
        $query = "select id from authors where name=:author";
        $bind[":author"] = $this->author;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) == 0) {
            $fields["name"] = $this->author;
            $this->db->insert("authors", $fields);
            $this->authorId = $this->db->getLastId();
        } else {
            $this->authorId = $rows[0]["id"];
        }
    }

    private function resolvePublisher() {
        $query = "select id from publishers where name=:publisher";
        $bind[":publisher"] = $this->publisher;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) == 0) {
            $fields["name"] = $this->publisher;
            $this->db->insert("publishers", $fields);
            $this->publisherId = $this->db->getLastId();
        } else {
            $this->publisherId = $rows[0]["id"];
        }
    }

    public function readOne() {
        $query = "SELECT books.id, books.name, books.sectionId, books.bookcaseId, authors.name as authorName, publishers.name as publisherName, IFNULL(books.releaseYear, '') as releaseYear, books.copies"
                . " FROM books"
                . " LEFT JOIN authors"
                . " ON books.authorId=authors.id"
                . " LEFT JOIN publishers"
                . " ON books.publisherId=publishers.id"
                . " WHERE books.id=:id";

        $bind[":id"] = $this->id;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) == 1) {
            $book = $rows[0];
            $this->name = $book["name"];
            $this->sectionId = $book["sectionId"];
            $this->bookcaseId = $book["bookcaseId"];
            $this->author = $book["authorName"];
            $this->publisher = $book["publisherName"];
            $this->releaseYear = $book["releaseYear"];
            $this->copies = $book["copies"];

            return true;
        } else {
            return false;
        }
    }

    public function readAll() {
        return $this->db->query("SELECT books.id, books.name, sections.name as sectionName, books.bookcaseId, authors.name as authorName, publishers.name as publisherName, IFNULL(books.releaseYear, '') as releaseYear, books.copies"
                        . " FROM books"
                        . " LEFT JOIN sections"
                        . " ON books.sectionId=sections.id"
                        . " LEFT JOIN authors"
                        . " ON books.authorId=authors.id"
                        . " LEFT JOIN publishers"
                        . " ON books.publisherId=publishers.id"
        );
    }

    public function readAllBooksForBorrow() {
        return $this->db->query("SELECT books.id, books.name, authors.name as authorName, IF(COUNT(borrowed_books.bookId)>=books.copies OR books.copies=0, false, true) as available, borrowed_books.boolReturn"
                        . " FROM books"
                        . " LEFT JOIN authors ON books.authorId=authors.id"
                        . " LEFT JOIN borrowed_books ON books.id = borrowed_books.bookId AND borrowed_books.boolReturn=0"
                        . " GROUP BY books.id"
        );
    }

}
