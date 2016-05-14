<?php

include "globalFunctions.php";
addReader();

function addReader() {
    $reader = Factory::makeReader();
    $param = new Param();
    $validator = Factory::makeInputValidator("AddReader");
    if (!$validator->validate(&$param->getArray())) {
        Factory::write($validator->errorLogger->getErrors());
        return;
    }
    $reader->id = $param->get("id");
    $reader->name = $param->get("name");
    $reader->city = $param->get("city");
    $reader->street = $param->get("street");
    $reader->readerType = $param->get("readerType");
    $reader->maxBooks = $param->get("maxBooks");

    $proxy = new ProxyAddReader($reader);
    $proxy->setUser(Factory::makeUser());
    if ($proxy->create($validator->errorLogger, $proxy->getUserId())) {
        $output["success"] = true;
    } else {
        $output = $validator->errorLogger->getErrors();
    }
    Factory::write($output);
}