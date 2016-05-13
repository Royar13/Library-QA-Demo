<?php

include "globalFunctions.php";
addReader();

function addReader() {
    $reader = Factory::makeReader();
    $param = Param::getArray();
    $validator = Factory::makeInputValidator("AddReader");
    if (!$validator->validate($param)) {
        Factory::write($validator->errorLogger->getErrors());
        return;
    }
    $reader->id = $param["id"];
    $reader->name = $param["name"];
    $reader->city = $param["city"];
    $reader->street = $param["street"];
    $reader->readerType = $param["readerType"];
    $reader->maxBooks = $param["maxBooks"];

    $proxy = new ProxyAddReader($reader);
    $proxy->setUser(Factory::makeUser());
    if ($proxy->create($validator->errorLogger, $proxy->getUserId())) {
        $output["success"] = true;
    } else {
        $output = $validator->errorLogger->getErrors();
    }
    Factory::write($output);
}