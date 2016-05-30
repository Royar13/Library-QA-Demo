<?php

function createReader() {
    enforcePermission(2);

    $reader = Factory::makeReader();
    $param = new Param();
    assignReaderData($reader, $param);
    $validator = Factory::makeValidator("Reader");

    if ($reader->create($validator, Factory::getUser()->id)) {
        $output["success"] = true;
    } else {
        $output = $validator->getErrors();
    }
    Factory::write($output);
}

function updateReader() {
    enforcePermission(3);

    $reader = Factory::makeReader();
    $param = new Param();
    assignReaderData($reader, $param);
    $validator = Factory::makeValidator("Reader");

    if ($reader->update($validator, Factory::getUser()->id)) {
        $output["success"] = true;
    } else {
        $output = $validator->getErrors();
    }
    Factory::write($output);
}

function assignReaderData($reader, $param) {
    $reader->id = $param->get("id");
    $reader->name = $param->get("name");
    $reader->city = $param->get("city");
    $reader->street = $param->get("street");
    $reader->readerType = $param->get("readerType");
    $reader->maxBooks = $param->get("maxBooks");
}

function readReader() {
    enforcePermission(1);

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
    enforcePermission(1);

    $db = Factory::$database;

    $result = $db->query("select * from reader_types");

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $output["readerTypes"][] = $row;
    }
    Factory::write($output);
}

function readAllReaders() {
    enforcePermission(1);

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

function readerExists() {
    enforcePermission(1);

    $param = new Param();
    $validator = Factory::makeValidator("Reader");
    if ($validator->validateIDExist($param->get("id"))) {
        $output["success"] = true;
    } else {
        $output["success"] = false;
        $output["errors"]["general"][] = "לא קיים קורא עם ת.ז. זו";
    }
    Factory::write($output);
}

function deleteReader() {
    enforcePermission(4);

    $reader = Factory::makeReader();
    $param = new Param();
    $reader->id = $param->get("id");
    $reader->readOne();
    $validator = Factory::makeValidator("Reader");
    if ($reader->delete($validator, Factory::getUser()->id)) {
        $output["success"] = true;
    } else {
        $output = $validator->getErrors();
    }
    Factory::write($output);
}
