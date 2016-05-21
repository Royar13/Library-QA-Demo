<?php

function createBook() {
    $book = Factory::makeBook();
    $param = new Param();
    $validator = new CreateBookValidator();
    $arr = &$param->getArray();
    if (!$validator->validate($arr)) {
        Factory::write($validator->errorLogger->getErrors());
        return;
    }

    assignBookData($book, $param);

    if ($book->create($validator->errorLogger, Factory::getUser()->id)) {
        $output["success"] = true;
        $output["id"] = $book->id;
    } else {
        $output = $validator->errorLogger->getErrors();
    }
    Factory::write($output);
}

function updateBook() {
    $book = Factory::makeBook();
    $param = new Param();
    $validator = new CreateBookValidator();
    $arr = &$param->getArray();
    if (!$validator->validate($arr)) {
        Factory::write($validator->errorLogger->getErrors());
        return;
    }

    assignBookData($book, $param);

    if ($book->update($validator->errorLogger, Factory::getUser()->id)) {
        $output["success"] = true;
    } else {
        $output = $validator->errorLogger->getErrors();
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
    if ($book->readOne()) {
        $output["name"] = $book->name;
        $output["sectionId"] = $book->sectionId;
        $output["bookcaseId"] = $book->bookcaseId;
        $output["author"] = $book->author;
        $output["publisher"] = $book->publisher;
        $output["releaseYear"] = $book->releaseYear;
        $output["copies"] = $book->copies;

        Factory::write($output);
    }
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
