<?php

class Factory {

    public static $database;

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
        return $user;
    }

    public static function makeInputValidator($type = "base") {
        switch($type) {
            case "base":
                $inputValidator = new InputValidator();
                break;
            case "AddReader":
                $inputValidator = new AddReaderValidator();
                break;
        }
        
        $inputValidator->setDatabase(self::$database);
        return $inputValidator;
    }

}

?>