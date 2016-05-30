<?php

function readAllPublishers() {
    enforcePermission(5);

    $db = Factory::$database;

    $result = $db->query("select * from publishers");

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $output["publishers"][] = $row;
    }
    Factory::write($output);
}
