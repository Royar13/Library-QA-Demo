<?php

class Reader implements IDatabaseAccess {

    private $db;
    public $id;
    public $name;
    public $city;
    public $street;
    public $readerType;
    public $maxBooks;

    public function setDatabase($db) {
        $this->db = $db;
    }

    public function create(ErrorLogger $errorLogger, $userId) {
        $this->validateReaderType($errorLogger);
        $this->validateMaxBooks($errorLogger);
        $this->validateIDNew($errorLogger);
        if (empty($this->city) && !empty($this->street)) {
            $errorLogger->addError("city", "יש למלא גם את העיר");
        }
        if (!empty($this->city) && empty($this->street)) {
            $errorLogger->addError("street", "יש למלא גם את הרחוב");
        }
        if (!$errorLogger->isValid()) {
            return false;
        }

        $fields["id"] = $this->id;
        $fields["name"] = $this->name;
        $fields["city"] = $this->city;
        $fields["street"] = $this->street;
        $fields["readerType"] = $this->readerType;
        $fields["maxBooks"] = $this->maxBooks;
        try {
            $this->db->insert("readers", $fields);

            $fields = array();
            $fields["userId"] = $userId;
            $fields["readerId"] = $this->id;
            $fields["description"] = "המשתמש {user} יצר את הקורא {reader}";
            $this->db->insert("readers_actions", $fields);
            return true;
        } catch (Exception $ex) {
            $errorLogger->addGeneralError("שגיאת מסד נתונים");
            return false;
        }
    }

    public function update(ErrorLogger $errorLogger, $userId) {
        $this->validateReaderType($errorLogger);
        $this->validateMaxBooks($errorLogger);
        $this->validateIDExist($errorLogger);
        if (!$errorLogger->isValid()) {
            return false;
        }

        try {
            $fields = array();
            $fields["name"] = $this->name;
            $fields["city"] = $this->city;
            $fields["street"] = $this->street;
            $fields["readerType"] = $this->readerType;
            $fields["maxBooks"] = $this->maxBooks;

            $condition["id"] = $this->id;
            $this->db->update("readers", $fields, $condition);

            $fields = array();
            $fields["userId"] = $userId;
            $fields["readerId"] = $this->id;
            $fields["description"] = "המשתמש {user} עדכן את הקורא {reader}";
            $this->db->insert("readers_actions", $fields);
            return true;
        } catch (Exception $ex) {
            $errorLogger->addGeneralError("שגיאת מסד נתונים: " . $ex->getMessage());
            return false;
        }
    }

    public function readOne() {
        $query = "select * from readers where id=:id";
        $bind[":id"] = $this->id;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) == 1) {
            $reader = $rows[0];
            $this->name = $reader["name"];
            $this->city = $reader["city"];
            $this->street = $reader["street"];
            $this->readerType = $reader["readerType"];
            $this->maxBooks = $reader["maxBooks"];
            return true;
        }
        return false;
    }

    public function readAll() {
        return $this->db->query("SELECT readers.id, readers.name, readers.city, readers.street, reader_types.title as readerType, readers.maxBooks, readers.joinDate"
                        . " FROM readers"
                        . " JOIN reader_types"
                        . " ON readers.readerType=reader_types.id");
    }

    private function validateReaderType($errorLogger) {
        $query = "select id from reader_types where id=:readerType";
        $bind[":readerType"] = $this->readerType;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) != 1) {
            $errorLogger->addError("readerType", "סוג קורא לא תקין");
        }
    }

    private function validateMaxBooks($errorLogger) {
        $query = "select maxBooks from allowed_books_num where maxBooks=:maxBooks";
        $bind[":maxBooks"] = $this->maxBooks;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) != 1) {
            $errorLogger->addError("maxBooks", "מספר ספרים לא תקין");
        }
    }

    private function validateIDNew($errorLogger) {
        $query = "select id from readers where id=:id";
        $bind[":id"] = $this->id;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) == 1) {
            $errorLogger->addError("id", "ת.ז. כבר קיימת במערכת");
            return;
        }

        /* $counter = 0;
          for ($i = 0; $i < strlen($value); $i++) {
          $digit = intval($value[$i]);
          $incNum = $digit * (($i % 2) + 1);
          $counter+=($incNum > 9) ? $incNum - 9 : $incNum;
          }
          if ($counter % 10 != 0) {
          $errorLogger->addError("id", "ת.ז. לא תקינה");
          } */
    }
    
    public function validateIDExist($errorLogger) {
        $query = "select id from readers where id=:id";
        $bind[":id"] = $this->id;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) != 1) {
            $errorLogger->addGeneralError("לא נמצא קורא עם תעודת הזהות");
            return;
        }
    }

    public function borrowReturnBooks($borrowBooksId, $returnBooksId, $errorLogger) {
        try {
            $this->db->query("LOCK TABLES borrowed_books READ");
            $borrowIdsStr = join(",", $borrowBooksId);
            $query = "SELECT COUNT(borrowed_books.bookId) as borrows, books.copies"
                    . " FROM books"
                    . " LEFT JOIN borrowed_books ON books.id = borrowed_books.bookId"
                    . " GROUP BY books.id"
                    . " HAVING books.id in(:borrowIdsStr) AND borrowed_books.boolReturn=0";
            $bind[":borrowIdsStr"] = $borrowIdsStr;
            $result = $this->db->preparedQuery($query, $bind);
            $con = true;
            while ($row = $result->fetch(PDO::FETCH_ASSOC) && $con) {
                if ($row["borrows"] >= $row["copies"]) {
                    $errorLogger->addGeneralError("לא כל הספרים המבוקשים פנויים להשאלה");
                    $con = false;
                }
            }
            $this->db->query("UNLOCK TABLES");
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

}
