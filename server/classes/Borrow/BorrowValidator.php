<?php

class BorrowValidator extends InputValidator implements IDatabaseAccess {

    private $db;

    public function setDatabase($db) {
        $this->db = $db;
    }

    public function validate($obj, $maxBooks, $borrowedBooks) {
        if (count($obj->borrowBooksIds) == 0 && count($obj->returnBooksIds) == 0) {
            $this->addGeneralError("לא נבחרו ספרים");
            return false;
        }
        try {
            //$this->db->query("LOCK TABLES borrowed_books read, books read");
            //$this->db->query("UNLOCK TABLES");
            $this->validateCanBorrowBooks($obj->borrowBooksIds, $this->errorLogger);

            if (!$this->validateNotBorrowed($obj->borrowBooksIds, $borrowedBooks))
                $this->addGeneralError("אי אפשר להשאיל שוב ספר שכבר מושאל על ידי הקורא");

            if (!$this->validateNoBorrowRepeat($obj->borrowBooksIds))
                $this->addGeneralError("אי אפשר להשאיל פעמיים את אותו הספר");

            if (!$this->validateCanReturnBooks($borrowedBooks, $obj->returnBooksIds))
                $this->addGeneralError("ספרים להחזרה לא תקינים");

            $allowedBorrowNum = $maxBooks - count($borrowedBooks) + count($obj->returnBooksIds);
            if (count($obj->borrowBooksIds) > $allowedBorrowNum)
                $this->addGeneralError("חרגת ממס' הספרים המותר להשאלה במקביל");

            return $this->isValid();
        } catch (Exception $ex) {
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
        
        if ($count < count($borrowBooksId))
            $errorLogger->addGeneralError("לא נמצאו כל הספרים להשאלה");
    }

}
