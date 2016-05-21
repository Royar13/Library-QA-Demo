<?php

function readAllAuthors() {
    $db = Factory::$database;

    $result = $db->query("select * from authors");

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $output["authors"][] = $row;
    }
    Factory::write($output);
}
