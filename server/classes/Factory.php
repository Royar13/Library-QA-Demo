<?php

class Factory {

    public static $database;
    public static $writer;
    private static $user;

    public static function write($arr) {
        self::$writer->write($arr);
    }

    public static function makeBook() {
        $book = new Book();
        $book->setDatabase(self::$database);
        return $book;
    }

    public static function makeReader() {
        $reader = new Reader();
        $reader->setDatabase(self::$database);
        return $reader;
    }

    public static function makeBookBorrow() {
        $borrow = new BookBorrow();
        $borrow->setDatabase(self::$database);
        return $borrow;
    }
    
    public static function getUser() {
        if (self::$user == null) {
            self::$user = new User();
            self::$user->setDatabase(self::$database);
        }
        return self::$user;
    }

}
