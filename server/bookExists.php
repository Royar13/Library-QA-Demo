<?php

include "globalFunctions.php";
include "userPermission.php";

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
