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

    public static function makeFormValidator($type) {
        switch ($type) {
            case "login":
                $formValidator = new LoginValidator();
                break;
        }
        $formValidator->setDatabase(self::$database);
        return $formValidator;
    }

    public static function makeFormField($value, $isRequired = false, $validations = array(), $sanitations = array("db")) {
        $formField = new FormField($value, $isRequired, $validations, $sanitations);
        $formField->setDatabase(self::$database);
        return $formField;
    }

}

?>