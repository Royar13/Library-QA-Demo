<?php

class Book implements IDatabaseAccess, ICreate {

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
            $result = $this->db->query("select id from authors where name='{$this->author}'");
            if (mysqli_num_rows($result) == 0) {
                $fields["name"] = $this->author;
                $this->db->insert("authors", $fields);
                $this->authorId = $this->db->getLastId();
            } else {
                $author = mysqli_fetch_assoc($result);
                $this->authorId = $author["id"];
            }

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
            $errorLogger->addGeneralError("שגיאת מסד נתונים");
            return false;
        }
    }

    function validateSection($errorLogger) {
        $result = $this->db->query("select bookcaseAmount from sections where id='{$this->sectionId}'");
        if (mysqli_num_rows($result) == 1) {
            $section = mysqli_fetch_assoc($result);
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
