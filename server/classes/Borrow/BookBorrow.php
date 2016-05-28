<?php

class BookBorrow implements IDatabaseAccess {

    private $db;
    public $readerId;
    public $borrowBooksIds;
    public $returnBooksIds;

    public function setDatabase($db) {
        $this->db = $db;
    }

    public function borrowReturnBooks(BorrowValidator $validator, $maxBooks, $userId) {
        $borrowedBooks = $this->readBorrowsByReader()->fetchAll(PDO::FETCH_ASSOC);
        if (!$validator->validate($this, $maxBooks, $borrowedBooks))
            return false;

        try {
            foreach ($this->borrowBooksIds as $id) {
                $fields = array();
                $fields["bookId"] = $id;
                $fields["readerId"] = $this->readerId;
                $fields["borrowUserId"] = $userId;
                $this->db->insert("borrowed_books", $fields);
            }

            if (count($this->returnBooksIds) > 0) {
                $bind = array();
                $bind["returnUserId"] = $userId;
                for ($i = 0; $i < count($borrowedBooks); $i++) {
                    $borrow = $borrowedBooks[$i];
                    if (in_array($borrow["bookId"], $this->returnBooksIds)) {
                        $condition[] = ":where" . $i;
                        $bind[":where" . $i] = $borrow["id"];
                    }
                }
                $conditionStr = join(",", $condition);
                $query = "UPDATE borrowed_books"
                        . " SET boolReturn=1, returnDate=now(), returnUserId=:returnUserId"
                        . " WHERE id in({$conditionStr})";
                $this->db->preparedQuery($query, $bind);
            }
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function readBorrowsByReader() {
        $query = "SELECT borrowed_books.*, books.name as bookName, authors.name as authorName"
                . " FROM borrowed_books"
                . " JOIN books ON borrowed_books.bookId=books.id"
                . " LEFT JOIN authors ON books.authorId=authors.id"
                . " WHERE readerId=:readerId AND boolReturn=0";
        $bind[":readerId"] = $this->readerId;
        $result = $this->db->preparedQuery($query, $bind);
        return $result;
    }

    public function readBorrowsByReaderForDisplay() {
        $borrowRules = $this->readBorrowRules()->fetchAll(PDO::FETCH_ASSOC)[0];
        $query = "SELECT borrowed_books.*,"
                . " (DATEDIFF(IF(borrowed_books.boolReturn, borrowed_books.returnDate, now()),borrowed_books.borrowDate)-{$borrowRules["borrowDays"]}) as lateDays,"
                . " (IF((DATEDIFF(IF(borrowed_books.boolReturn, borrowed_books.returnDate, now()),borrowed_books.borrowDate)-{$borrowRules["borrowDays"]})>0, true, false)) as isLate,"
                . " ((DATEDIFF(IF(borrowed_books.boolReturn, borrowed_books.returnDate, now()),borrowed_books.borrowDate)-{$borrowRules["borrowDays"]})*{$borrowRules["dailyFine"]}) as fine,"
                . " books.name as bookName, authors.name as authorName"
                . " FROM borrowed_books"
                . " JOIN books ON borrowed_books.bookId=books.id"
                . " LEFT JOIN authors ON books.authorId=authors.id"
                . " WHERE readerId=:readerId AND boolReturn=0";
        $bind[":readerId"] = $this->readerId;
        $result = $this->db->preparedQuery($query, $bind);
        return $result;
    }

    public function readAllBorrowsByReader() {
        $borrowRules = $this->readBorrowRules()->fetchAll(PDO::FETCH_ASSOC)[0];
        $query = "SELECT borrowed_books.*,"
                . " (DATEDIFF(IF(borrowed_books.boolReturn, borrowed_books.returnDate, now()),borrowed_books.borrowDate)-{$borrowRules["borrowDays"]}) as lateDays,"
                . " (IF((DATEDIFF(IF(borrowed_books.boolReturn, borrowed_books.returnDate, now()),borrowed_books.borrowDate)-{$borrowRules["borrowDays"]})>0, true, false)) as isLate,"
                . " ((DATEDIFF(IF(borrowed_books.boolReturn, borrowed_books.returnDate, now()),borrowed_books.borrowDate)-{$borrowRules["borrowDays"]})*{$borrowRules["dailyFine"]}) as fine,"
                . " books.name as bookName, authors.name as authorName"
                . " FROM borrowed_books"
                . " JOIN books ON borrowed_books.bookId=books.id"
                . " LEFT JOIN authors ON books.authorId=authors.id"
                . " WHERE readerId=:readerId";
        $bind[":readerId"] = $this->readerId;
        $result = $this->db->preparedQuery($query, $bind);
        return $result;
    }

    public function readAllBorrowsByBook() {
        $borrowRules = $this->readBorrowRules()->fetchAll(PDO::FETCH_ASSOC)[0];
        $query = "SELECT borrowed_books.*,"
                . " (DATEDIFF(IF(borrowed_books.boolReturn, borrowed_books.returnDate, now()),borrowed_books.borrowDate)-{$borrowRules["borrowDays"]}) as lateDays,"
                . " (IF((DATEDIFF(IF(borrowed_books.boolReturn, borrowed_books.returnDate, now()),borrowed_books.borrowDate)-{$borrowRules["borrowDays"]})>0, true, false)) as isLate,"
                . " ((DATEDIFF(IF(borrowed_books.boolReturn, borrowed_books.returnDate, now()),borrowed_books.borrowDate)-{$borrowRules["borrowDays"]})*{$borrowRules["dailyFine"]}) as fine,"
                . " readers.name as readerName, readers.id as readerId"
                . " FROM borrowed_books"
                . " JOIN readers ON borrowed_books.readerId=readers.id"
                . " WHERE bookId=:bookId";
        $bind[":bookId"] = $this->bookId;
        $result = $this->db->preparedQuery($query, $bind);
        return $result;
    }

    public function readBorrowRules() {
        return $this->db->query("select * from borrow_rules");
    }

}
