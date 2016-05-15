<?php
include "globalFunctions.php";
include "userPermission.php";

borrowReturnBooks();

function borrowReturnBooks() {
    $reader = Factory::makeReader();
    $param = new Param();
    $reader->id
            $param->get("name");


    if ($book->create($validator->errorLogger, Factory::getUser()->id)) {
        $output["success"] = true;
    } else {
        $output = $validator->errorLogger->getErrors();
    }
    Factory::write($output);
}

