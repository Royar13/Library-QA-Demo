<?php

class ProxyAddBook extends UserProxy implements ICreate {

    private $book;

    public function __construct(Book $book) {
        $this->book = $book;
    }

    public function create(ErrorLogger $errorLogger, $userId) {
        if (!$this->user->authenticate()) {
            throw new Exception();
        }
        return $this->book->create($errorLogger, $userId);
    }

}