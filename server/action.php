<?php

function readActionsByReader() {
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