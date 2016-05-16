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
        if (!$this->validateCity()) {
            $errorLogger->addError("city", "יש למלא גם את העיר");
        }
        if (!$this->validateStreet()) {
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
        if (!$this->validateCity()) {
            $errorLogger->addError("city", "יש למלא גם את העיר");
        }
        if (!$this->validateStreet()) {
            $errorLogger->addError("street", "יש למלא גם את הרחוב");
        }
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

    public function getBorrowedBooks() {
        $query = "SELECT borrowed_books.*, books.name"
                . " FROM borrowed_books"
                . " JOIN books ON borrowed_books.bookId=books.id"
                . " WHERE readerId=:id AND boolReturn=0";
        $bind[":id"] = $this->id;
        $result = $this->db->preparedQuery($query, $bind);
        return $result;
    }

    public function readAll() {
        return $this->db->query("SELECT readers.id, readers.name, readers.city, readers.street, reader_types.title as readerType, readers.maxBooks, readers.joinDate"
                        . " FROM readers"
                        . " JOIN reader_types"
                        . " ON readers.readerType=reader_types.id");
    }

    public function validateCity() {
        return !(empty($this->city) && !empty($this->street));
    }

    public function validateStreet() {
        return !(!empty($this->city) && empty($this->street));
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

    public function borrowReturnBooks($borrowBooksId, $returnBooksId, $errorLogger, $userId) {
        try {
            //$this->db->query("LOCK TABLES borrowed_books read, books read");
            //$this->db->query("UNLOCK TABLES");
            $this->validateCanBorrowBooks($borrowBooksId, $errorLogger);

            $borrowedBooks = $this->getBorrowedBooks()->fetchAll(PDO::FETCH_ASSOC);
            if (!$this->validateNotBorrowed($borrowBooksId, $borrowedBooks)) {
                $errorLogger->addGeneralError("אי אפשר להשאיל שוב ספר שכבר מושאל על ידי הקורא");
            }
            if (!$this->validateNoBorrowRepeat($borrowBooksId)) {
                $errorLogger->addGeneralError("אי אפשר להשאיל פעמיים את אותו הספר");
            }
            if (!$this->validateCanReturnBooks($borrowedBooks, $returnBooksId)) {
                $errorLogger->addGeneralError("ספרים להחזרה לא תקינים");
            }
            $allowedBorrowNum = $this->maxBooks - count($borrowedBooks) + count($returnBooksId);
            if (count($borrowBooksId) > $allowedBorrowNum) {
                $errorLogger->addGeneralError("חרגת ממס' הספרים המותר להשאלה במקביל");
            }

            if (!$errorLogger->isValid()) {
                return false;
            }
            foreach ($borrowBooksId as $id) {
                $fields = array();
                $fields["bookId"] = $id;
                $fields["readerId"] = $this->id;
                $fields["borrowUserId"] = $userId;
                $this->db->insert("borrowed_books", $fields);
            }
            if (count($returnBooksId) > 0) {
                $bind = array();
                $bind["returnUserId"] = $userId;
                for ($i = 0; $i < count($borrowedBooks); $i++) {
                    $borrow = $borrowedBooks[$i];
                    if (in_array($borrow["bookId"], $returnBooksId)) {
                        $condition[] = ":where" . $i;
                        $bind[":where" . $i] = $borrow["id"];
                    }
                }
                $conditionStr = join(",", $condition);
                $query = "UPDATE borrowed_books"
                        . " SET boolReturn=1, returnDate=now(), returnUserId=:returnUserId"
                        . " WHERE id in({$conditionStr})";
                $result = $this->db->preparedQuery($query, $bind);
            }
            return true;
        } catch (Exception $ex) {
            $errorLogger->addGeneralError("שגיאת מסד נתונים: " . $ex->getMessage());
            return false;
        }
    }

    private function validateCanReturnBooks($borrowedBooks, $returnBooksId) {
        $count = 0;
        foreach ($borrowedBooks as $borrow) {
            if (in_array($borrow["bookId"], $returnBooksId)) {
                $count++;
            }
        }
        return count($returnBooksId) == $count;
    }

    private function validateNotBorrowed($borrowBooksId, $borrowedBooks) {
        foreach ($borrowBooksId as $id) {
            foreach ($borrowedBooks as $borrow) {
                if ($id == $borrow["bookId"]) {
                    return false;
                }
            }
        }

        return true;
    }

    private function validateNoBorrowRepeat($borrowBooksId) {
        $repeats = array();
        foreach ($borrowBooksId as $id) {
            if (!isset($repeats[$id])) {
                $repeats[$id] = 1;
            } else {
                return false;
            }
        }
        return true;
    }

    private function validateCanBorrowBooks($borrowBooksId, $errorLogger) {
        if (count($borrowBooksId) == 0)
            return;
        for ($i = 0; $i < count($borrowBooksId); $i++) {
            $bind[":borrow" . $i] = $borrowBooksId[$i];
            $strArr[] = ":borrow" . $i;
        }
        $str = join(",", $strArr);
        $query = "SELECT books.name, COUNT(borrowed_books.bookId) as borrows, books.copies"
                . " FROM books"
                . " LEFT JOIN borrowed_books ON books.id = borrowed_books.bookId AND borrowed_books.boolReturn=0"
                . " GROUP BY books.id"
                . " HAVING books.id in({$str})";
        $result = $this->db->preparedQuery($query, $bind);
        $count = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $count++;
            if ($row["borrows"] >= $row["copies"] || $row["copies"] == 0) {
                $errorLogger->addGeneralError("הספר '{$row["name"]}' אינו פנוי להשאלה");
            }
        }
        if ($count < count($borrowBooksId)) {
            $errorLogger->addGeneralError("לא נמצאו כל הספרים להשאלה");
        }
    }

}
