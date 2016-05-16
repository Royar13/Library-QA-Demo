<?php

include "globalFunctions.php";
include "userPermission.php";

borrowReturnBooks();

function borrowReturnBooks() {
    $success = false;
    $reader = Factory::makeReader();
    $param = new Param();
    $reader->id = $param->get("id");
    $errorLogger = new ErrorLogger();
    if ($reader->readOne()) {
        $borrowBooksId = $param->get("borrowBooksId");
        $returnBooksId = $param->get("returnBooksId");
        $reader->borrowReturnBooks($borrowBooksId, $returnBooksId, $errorLogger);
    }
    else {
        $errorLogger->addGeneralError("לא נמצא קורא עם ת.ז. זו");
    }

    if ($errorLogger->isValid()) {
        $output["success"] = true;
    } else {
        $output = $errorLogger->getErrors();
    }
    Factory::write($output);
}
