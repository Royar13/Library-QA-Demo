<?php

function readAllSections() {
    $db = Factory::$database;

    $result = $db->query("select * from sections");

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $output["sections"][] = $row;
    }
    Factory::write($output);
}