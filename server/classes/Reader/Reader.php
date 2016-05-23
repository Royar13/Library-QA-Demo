<?php

class Reader implements IDatabaseAccess {

    private $db;
    public $id;
    public $name;
    public $city;
    public $street;
    public $readerType;
    public $maxBooks;
    public $joinDate;
    public $payments;

    public function setDatabase($db) {
        $this->db = $db;
    }

    public function create(ReaderValidator $validator, $userId) {
        if (!$validator->validateCreate($this))
            return false;

        $fields = $this->toArray();
        try {
            $this->db->insert("readers", $fields);

            $actionFields["userId"] = $userId;
            $actionFields["readerId"] = $this->id;
            $actionFields["description"] = "המשתמש {user} יצר את הקורא {reader}";
            $this->db->insert("readers_actions", $actionFields);
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function toArray() {
        $fields["id"] = $this->id;
        $fields["name"] = $this->name;
        $fields["city"] = $this->city;
        $fields["street"] = $this->street;
        $fields["readerType"] = $this->readerType;
        $fields["maxBooks"] = $this->maxBooks;
        return $fields;
    }

    public function update(ErrorLogger $errorLogger, $userId) {
        if (!$validator->validateUpdate($this))
            return false;

        try {
            $fields = $this->toArray();

            $condition["id"] = $this->id;
            $this->db->update("readers", $fields, $condition);

            $actionFields["userId"] = $userId;
            $actionFields["readerId"] = $this->id;
            $actionFields["description"] = "המשתמש {user} עדכן את הקורא {reader}";
            $this->db->insert("readers_actions", $actionFields);
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function readOne() {
        $query = "select readers.*, reader_types.bookCost from readers join reader_types on readers.readerType=reader_types.id where readers.id=:id";
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
            $this->joinDate = $reader["joinDate"];
            $joinDate = new DateTime($this->joinDate);
            $now = new DateTime();
            $months = $now->diff($joinDate)->format("%m");
            $this->payments = $reader["bookCost"] * $this->maxBooks * $months;
            return true;
        }
        return false;
    }

    public function getBorrowedBooks() {
        $query = "SELECT borrowed_books.*, books.name as bookName, authors.name as authorName"
                . " FROM borrowed_books"
                . " JOIN books ON borrowed_books.bookId=books.id"
                . " LEFT JOIN authors ON books.authorId=authors.id"
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

    public function borrowReturnBooks($borrowBooksId, $returnBooksId, $errorLogger, $userId) {
        if (count($borrowBooksId) == 0 && count($returnBooksId) == 0) {
            $errorLogger->addGeneralError("לא נבחרו ספרים");
            return false;
        }
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
