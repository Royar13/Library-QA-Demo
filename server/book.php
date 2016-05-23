<?php

function createBook() {
    $book = Factory::makeBook();
    $param = new Param();
    assignBookData($book, $param);
    $validator = Factory::makeValidator("Book");

    if ($book->create($validator, Factory::getUser()->id)) {
        $output["success"] = true;
        $output["id"] = $book->id;
    } else {
        $output = $validator->getErrors();
    }
    Factory::write($output);
}

function updateBook() {
    $book = Factory::makeBook();
    $param = new Param();
    assignBookData($book, $param->getArray());
    $validator = Factory::makeValidator("CreateBook");

    if ($book->update($validator, Factory::getUser()->id)) {
        $output["success"] = true;
    } else {
        $output = $validator->getErrors();
    }
    Factory::write($output);
}

function assignBookData($book, $param) {
    $book->id = $param->get("id");
    $book->name = $param->get("name");
    $book->sectionId = $param->get("sectionId");
    $book->bookcaseId = $param->get("bookcaseId");
    $book->author = $param->get("author");
    $book->publisher = $param->get("publisher");
    $book->releaseYear = $param->get("releaseYear");
    if (empty($book->releaseYear)) {
        $book->releaseYear = null;
    }
    $book->copies = $param->get("copies");
}

function bookExists() {
    $book = Factory::makeBook();
    $param = new Param();
    $book->id = $param->get("id");
    $errorLogger = new ErrorLogger();
    $result = $book->validateIdExist($errorLogger);
    if ($errorLogger->isValid()) {
        $output["success"] = true;
    } else {
        $output = $errorLogger->getErrors();
    }
    Factory::write($output);
}

function readBook() {
    $book = Factory::makeBook();
    $param = new Param();
    $book->id = $param->get("id");
    $book->readOne();
    $output = $book->toArray();
    Factory::write($output);
}

function readAllBooks() {
    $book = Factory::makeBook();
    $result = $book->readAll();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $output["books"][] = $row;
    }
    Factory::write($output);
}

function readBooksNum() {
    $db = Factory::$database;

    $result = $db->query("select * from allowed_books_num");
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $output["booksNum"][] = $row["maxBooks"];
    }
    Factory::write($output);
}

function readAllBooksForBorrow() { //change class function name to this
    $book = Factory::makeBook();
    $result = $book->readAllBorrowAPI();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $output["books"][] = $row;
    }
    Factory::write($output);
}
