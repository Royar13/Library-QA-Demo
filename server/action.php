<?php

function readActionsByReader() {
    enforcePermission(1);

    $param = new Param();
    $action = new ReaderAction(Factory::$database, $param->get("id"), null, null);
    $result = $action->readAll();
    $output["actions"] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $row["description"] = $action->formatDescription($row["description"], $row["username"], $row["readerName"]);
        $output["actions"][] = $row;
    }
    Factory::write($output);
}

function readActionsByBook() {
    enforcePermission(5);

    $param = new Param();
    $action = new BookAction(Factory::$database, $param->get("id"), null, null);
    $result = $action->readAll();
    $output["actions"] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $row["description"] = $action->formatDescription($row["description"], $row["username"], $row["bookName"]);
        $output["actions"][] = $row;
    }
    Factory::write($output);
}

function readActionsByUser() {
    enforcePermission(10);

    $param = new Param();
    $user = Factory::makeUser();
    $user->id = $param->get("id");
    $user->readOne();
    $action = new Action(Factory::$database, null, $param->get("id"), null);
    $result = $action->readAllByUser();
    $output["actions"] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $row["description"] = $action->formatDescription($row["description"], $user->username, $row["name"]);
        $output["actions"][] = $row;
    }
    Factory::write($output);
}
