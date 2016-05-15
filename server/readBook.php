<?php

include "globalFunctions.php";
include "userPermission.php";

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
