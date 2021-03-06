<?php

class BookValidator extends InputValidator implements IDatabaseAccess {

    private $db;

    public function __construct() {
        parent::__construct();
        $this->mandatories = array("name", "sectionId", "bookcaseId", "author");
        $this->setValidation("name", "hebrewTitle");
        $this->setValidation("sectionId", "positiveInt");
        $this->setValidation("bookcaseId", "positiveInt");
        $this->setValidation("author", "hebrew");
        $this->setValidation("publisher", "hebrew");
        $this->setValidation("releaseYear", "nonNegativeInt");
        $this->setValidation("copies", "nonNegativeInt");
    }

    public function setDatabase($db) {
        $this->db = $db;
    }

    public function validate($obj) {
        if (!$this->validateSyntax($obj))
            return false;

        if (!$this->validateSection($obj->sectionId, $obj->bookcaseId))
            $this->addGeneralError("מס' כוננית לא תקין");

        if (!$this->validateName($obj->id, $obj->name))
            $this->addError("name", "כבר קיים ספר עם שם זה");

        return $this->isValid();
    }

    public function validateUpdate($obj) {
        if (!$this->validate($obj))
            return false;
        if (!$this->validateIdExist($obj->id))
            $this->addGeneralError("לא נמצא הספר");
        if (!$this->validateNotBorrowedMoreThanCopies($obj->id, $obj->copies))
            $this->addError("copies", "הספר מושאל יותר מ-{$obj->copies} פעמים, לכן אי אפשר להפחית את מס' העותקים לערך זה");
        return $this->isValid();
    }

    public function validateDelete($book) {
        if (!$this->validateIdExist($book->id))
            $this->addGeneralError("לא נמצא הספר");
        if (!$this->validateNotBorrowed($book->id))
            $this->addGeneralError("אי אפשר למחוק ספר שנמצא בהשאלה");
        return $this->isValid();
    }

    public function validateIdExist($id) {
        $query = "select id from books where id=:id";
        $bind[":id"] = $id;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        return (count($rows) == 1);
    }

    public function validateName($id, $name) {
        $query = "select id from books where name=:name and id!=:id";
        $bind[":id"] = $id;
        $bind[":name"] = $name;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        return count($rows) == 0;
    }

    public function validateSection($sectionId, $bookcaseId) {
        $query = "select bookcaseAmount from sections where id=:sectionId";
        $bind[":sectionId"] = $sectionId;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) == 1) {
            $section = $rows[0];
            if ($bookcaseId <= $section["bookcaseAmount"]) {
                return true;
            }
        }
        return false;
    }

    public function validateNotBorrowed($id) {
        $query = "select id from borrowed_books where bookId=:bookId AND boolReturn=0";
        $bind[":bookId"] = $id;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        return (count($rows) == 0);
    }

    public function validateNotBorrowedMoreThanCopies($id, $copies) {
        $query = "select id from borrowed_books where bookId=:bookId AND boolReturn=0";
        $bind[":bookId"] = $id;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        return (count($rows) <= $copies);
    }

}
