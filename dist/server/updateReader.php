<?php
include "globalFunctions.php";
include "userPermission.php";

updateReader();

function updateReader() {
    $reader = Factory::makeReader();
    $param = new Param();
    $validator = new AddReaderValidator();
    $arr = &$param->getArray();
    if (!$validator->validate($arr)) {
        Factory::write($validator->errorLogger->getErrors());
        return;
    }
    $reader->id = $param->get("id");
    $reader->name = $param->get("name");
    $reader->city = $param->get("city");
    $reader->street = $param->get("street");
    $reader->readerType = $param->get("readerType");
    $reader->maxBooks = $param->get("maxBooks");

    if ($reader->update($validator->errorLogger, Factory::getUser()->id)) {
        $output["success"] = true;
    } else {
        $output = $validator->errorLogger->getErrors();
    }
    Factory::write($output);
}
