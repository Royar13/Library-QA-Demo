<?php

class BookBorrow implements IDatabaseAccess {

    private $db;
    public $id;
    public $readerId;

    public function setDatabase($db) {
        $this->db = $db;
    }

    public function readBorrowsForDisplay() {
        $borrowRules = $this->readBorrowRules()->fetchAll(PDO::FETCH_ASSOC)[0];
        $query = "SELECT borrowed_books.*, borrowed_books.borrowDate, (DATEDIFF(now(),borrowed_books.borrowDate)-{$borrowRules["borrowDays"]}) as lateDays,"
                . " IF((DATEDIFF(now(),borrowed_books.borrowDate)-{$borrowRules["borrowDays"]})>0, true, false) as isLate,"
                . " ((DATEDIFF(now(),borrowed_books.borrowDate)-{$borrowRules["borrowDays"]})*{$borrowRules["dailyFine"]}) as fine,"
                . " books.name as bookName, authors.name as authorName"
                . " FROM borrowed_books"
                . " JOIN books ON borrowed_books.bookId=books.id"
                . " LEFT JOIN authors ON books.authorId=authors.id"
                . " WHERE readerId=:id AND boolReturn=0";
        $bind[":id"] = $this->readerId;
        $result = $this->db->preparedQuery($query, $bind);
        return $result;
    }

    public function readBorrowRules() {
        return $this->db->query("select * from borrow_rules");
    }

}
