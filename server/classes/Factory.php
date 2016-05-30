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

    public static function makeValidator($type) {
        switch ($type) {
            case "Book":
                $validator = new BookValidator();
                break;
            case "Reader":
                $validator = new ReaderValidator();
                break;
            case "Borrow":
                $validator = new BorrowValidator();
                break;
            case "User":
                $validator = new UserValidator();
                break;
            case "CreateUser":
                $validator = new CreateUserValidator();
                break;
            case "UpdateUser":
                $validator = new UpdateUserValidator();
                break;
            case "UpdatePassword":
                $validator = new UpdatePasswordValidator();
                break;
        }
        $validator->setDatabase(self::$database);
        return $validator;
    }

    public static function getUser() {
        if (self::$user == null) {
            self::$user = new User();
            self::$user->setDatabase(self::$database);
        }
        return self::$user;
    }

    public static function makeUser() {
        $user = new User();
        $user->setDatabase(self::$database);
        return $user;
    }

}
