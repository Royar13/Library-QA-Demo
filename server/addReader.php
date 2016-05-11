<?php

include "globalFunctions.php";
addReader();

function addReader() {
    $reader = Factory::makeReader();
    $param = Param::getArray();
    $validator = Factory::makeInputValidator("AddReader");
    if (!$validator->validate($param)) {
        $validator->errorLogger->printJSON();
        return;
    }
    $reader->id = $param["id"];
    $reader->name = $param["name"];
    $reader->city = $param["city"];
    $reader->street = $param["street"];
    $reader->readerType = $param["readerType"];
    $reader->maxBooks = $param["maxBooks"];

    if ((new ProxyAddReader($reader))->create($validator->errorLogger)) {
        $output["success"] = true;
        echo json_encode($output);
    } else {
        $validator->errorLogger->printJSON();
    }
}

?>