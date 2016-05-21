<?php

function createReader() {
    $reader = Factory::makeReader();
    $param = new Param();
    $validator = new CreateReaderValidator();
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

    if ($reader->create($validator->errorLogger, Factory::getUser()->id)) {
        $output["success"] = true;
    } else {
        $output = $validator->errorLogger->getErrors();
    }
    Factory::write($output);
}

function readReader() {
    $reader = Factory::makeReader();
    $param = new Param();
    $reader->id = $param->get("id");
    $reader->readOne();

    $output["id"] = $reader->id;
    $output["name"] = $reader->name;
    $output["city"] = $reader->city;
    $output["street"] = $reader->street;
    $output["readerType"] = $reader->readerType;
    $output["maxBooks"] = $reader->maxBooks;
    $output["joinDate"] = $reader->joinDate;
    $output["payments"] = $reader->payments;

    Factory::write($output);
}

function readReaderTypes() {
    $db = Factory::$database;

    $result = $db->query("select * from reader_types");

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $output["readerTypes"][] = $row;
    }
    Factory::write($output);
}

function readAllReaders() {
    $reader = Factory::makeReader();
    $result = $reader->readAll();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $row["address"] = "";
        if (!empty($row["city"])) {
            $row["address"] = $row["street"] . ", " . $row["city"];
        }
        $output["readers"][] = $row;
    }
    Factory::write($output);
}

function updateReader() {
    $reader = Factory::makeReader();
    $param = new Param();
    $validator = new CreateReaderValidator();
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

function readerExists() {
    $reader = Factory::makeReader();
    $param = new Param();
    $reader->id = $param->get("id");
    $errorLogger = new ErrorLogger();
    $result = $reader->validateIDExist($errorLogger);
    if ($errorLogger->isValid()) {
        $output["success"] = true;
    } else {
        $output = $errorLogger->getErrors();
    }
    Factory::write($output);
}
