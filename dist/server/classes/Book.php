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

}
