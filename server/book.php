<?php

function createBook() {
    enforcePermission(6);

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
    enforcePermission(7);

    $book = Factory::makeBook();
    $param = new Param();
    assignBookData($book, $param);
    $validator = Factory::makeValidator("Book");

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
    $book->copies = $param->get("copies");
}

function bookExists() {
    enforcePermission(5);

    $validator = Factory::makeValidator("Book");
    $param = new Param();
    if ($validator->validateIdExist($param->get("id"))) {
        $output["success"] = true;
    } else {
        $output["success"] = false;
        $output["errors"]["general"][] = "לא נמצא ספר עם קוד זה";
    }
    Factory::write($output);
}

function readBook() {
    enforcePermission(5);

    $book = Factory::makeBook();
    $param = new Param();
    $book->id = $param->get("id");
    $book->readOne();
    $output["id"] = $book->id;
    $output["name"] = $book->name;
    $output["sectionId"] = $book->sectionId;
    $output["bookcaseId"] = $book->bookcaseId;
    $output["author"] = $book->author;
    $output["publisher"] = $book->publisher;
    $output["releaseYear"] = $book->releaseYear;
    $output["copies"] = $book->copies;
    Factory::write($output);
}

function readAllBooks() {
    enforcePermission(18);

    $book = Factory::makeBook();
    $result = $book->readAll();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $output["books"][] = $row;
    }
    Factory::write($output);
}

function readBooksNum() {
    enforcePermission(5);

    $db = Factory::$database;

    $result = $db->query("select * from allowed_books_num");
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $output["booksNum"][] = $row["maxBooks"];
    }
    Factory::write($output);
}

function readAllBooksForBorrow() {
    enforcePermission(5);

    $book = Factory::makeBook();
    $result = $book->readAllBooksForBorrow();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $output["books"][] = $row;
    }
    Factory::write($output);
}

function deleteBook() {
    enforcePermission(8);

    $book = Factory::makeBook();
    $param = new Param();
    $book->id = $param->get("id");
    $book->readOne();
    $validator = Factory::makeValidator("Book");
    if ($book->delete($validator, Factory::getUser()->id)) {
        $output["success"] = true;
    } else {
        $output = $validator->getErrors();
    }
    Factory::write($output);
}
