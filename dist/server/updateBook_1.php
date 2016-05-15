<?php
include "globalFunctions.php";
include "userPermission.php";

updateBook();

function updateBook() {
    $book = Factory::makeBook();
    $param = new Param();
    $validator = new AddBookValidator();
    $arr = &$param->getArray();
    if (!$validator->validate($arr)) {
        Factory::write($validator->errorLogger->getErrors());
        return;
    }
    $book->id = $param->get("id");
    $book->name = $param->get("name");
    $book->sectionId = $param->get("sectionId");
    $book->bookcaseId = $param->get("bookcaseId");
    $book->author = $param->get("author");
    $book->publisher = $param->get("publisher");
    $book->releaseYear = $param->get("releaseYear");
    $book->copies = $param->get("copies");

    if ($book->update($validator->errorLogger, Factory::getUser()->id)) {
        $output["success"] = true;
    } else {
        $output = $validator->errorLogger->getErrors();
    }
    Factory::write($output);
}
