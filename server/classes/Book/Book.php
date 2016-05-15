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

    public function create(ErrorLogger $errorLogger, $userId) {
        $this->validateSection($errorLogger);
        $this->validateName($errorLogger);
        if (!$errorLogger->isValid()) {
            return false;
        }

        try {
            $this->resolveAuthor();
            $this->resolvePublisher();

            $fields = array();
            $fields["name"] = $this->name;
            $fields["sectionId"] = $this->sectionId;
            $fields["bookcaseId"] = $this->bookcaseId;
            $fields["authorId"] = $this->authorId;
            $fields["publisherId"] = $this->publisherId;
            $fields["releaseYear"] = $this->releaseYear;
            $fields["copies"] = $this->copies;
            $this->db->insert("books", $fields);

            $fields = array();
            $fields["userId"] = $userId;
            $fields["bookId"] = $this->db->getLastId();
            $fields["description"] = "המשתמש {user} יצר את הספר {book}";
            $this->db->insert("books_actions", $fields);
            return true;
        } catch (Exception $ex) {
            $errorLogger->addGeneralError("שגיאת מסד נתונים: " . $ex->getMessage());
            return false;
        }
    }

    public function update(ErrorLogger $errorLogger, $userId) {
        $this->validateIdExist($errorLogger);
        $this->validateSection($errorLogger);
        $this->validateName($errorLogger);
        if (!$errorLogger->isValid()) {
            return false;
        }

        try {
            $this->resolveAuthor();
            $this->resolvePublisher();

            $fields = array();
            $fields["name"] = $this->name;
            $fields["sectionId"] = $this->sectionId;
            $fields["bookcaseId"] = $this->bookcaseId;
            $fields["authorId"] = $this->authorId;
            $fields["publisherId"] = $this->publisherId;
            $fields["releaseYear"] = $this->releaseYear;
            $fields["copies"] = $this->copies;

            $condition["id"] = $this->id;
            $this->db->update("books", $fields, $condition);

            $fields = array();
            $fields["userId"] = $userId;
            $fields["bookId"] = $this->id;
            $fields["description"] = "המשתמש {user} עדכן את הספר {book}";
            $this->db->insert("books_actions", $fields);
            return true;
        } catch (Exception $ex) {
            $errorLogger->addGeneralError("שגיאת מסד נתונים: " . $ex->getMessage());
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

    private function validateIdExist($errorLogger) {
        $query = "select id from books where id=:id";
        $bind[":id"] = $this->id;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) == 0) {
            $errorLogger->addGeneralError("לא נמצא הספר");
        }
    }

    private function validateName($errorLogger) {
        $query = "select id from books where name=:name and id!=:id";
        $bind[":id"] = $this->id;
        $bind[":name"] = $this->name;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) >= 1) {
            $errorLogger->addError("name", "כבר קיים ספר עם שם זה");
        }
    }

    private function validateSection($errorLogger) {
        $query = "select bookcaseAmount from sections where id=:sectionId";
        $bind[":sectionId"] = $this->sectionId;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) == 1) {
            $section = $rows[0];
            if ($this->bookcaseId <= $section["bookcaseAmount"]) {
                return true;
            } else {
                $errorLogger->addError("bookcaseId", "ערך לא תקין");
                return false;
            }
        } else {
            $errorLogger->addError("sectionId", "ערך לא תקין");
            return false;
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

    public function readAllBorrowAPI() {
        return $this->db->query("SELECT books.id, books.name, authors.name as authorName, IF(COUNT(borrowed_books.bookId)>=books.copies, false, true) as available, borrowed_books.boolReturn"
                        . " FROM books"
                        . " LEFT JOIN authors ON books.authorId=authors.id"
                        . " LEFT JOIN borrowed_books ON books.id = borrowed_books.bookId"
                        . " GROUP BY books.id"
                        . " HAVING borrowed_books.boolReturn=0"
        );
    }
}
