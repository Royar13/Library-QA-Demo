<?php

class Factory {

    private static $database;

    public static function __initStatic() {
        self::$database = new Database(array("servername" => "localhost", "username" => "root", "password" => "", "dbname" => "library"));
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

    public static function makeUser() {
        $user = new User();
        $user->setDatabase(self::$database);
        $user->setFormValidator(new FormValidator());
        return $user;
    }
}

?>