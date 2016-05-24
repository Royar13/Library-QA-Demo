<?php

class Factory {

    public static $database;
    public static $writer;
    private static $user;

    public static function __initStatic() {
        self::$database = new Database(array("servername" => "mysql.hostinger.co.il", "username" => "u990065029_roy", "password" => "H8ezDZt4b5", "dbname" => "u990065029_lib"));
        self::$writer = new JSONWriter();
    }

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
