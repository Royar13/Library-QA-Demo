<?php

include "globalFunctions.php";
addBook();

function addBook() {
    $book = Factory::makeBook();
    $param = new Param();
    $validator = Factory::makeInputValidator("AddBook");
    $arr = &$param->getArray();
    if (!$validator->validate($arr)) {
        Factory::write($validator->errorLogger->getErrors());
        return;
    }
    $book->name = $param->get("name");
    $book->sectionId = $param->get("sectionId");
    $book->bookcaseId = $param->get("bookcaseId");
    $book->author = $param->get("author");
    $book->publisher = $param->get("publisher");
    $book->releaseYear = $param->get("releaseYear");
    $book->copies = $param->get("copies");

    $proxy = new ProxyAddBook($book);
    $proxy->setUser(Factory::makeUser());
    if ($proxy->create($validator->errorLogger, $proxy->getUserId())) {
        $output["success"] = true;
    } else {
        $output = $validator->errorLogger->getErrors();
    }
    Factory::write($output);
}
