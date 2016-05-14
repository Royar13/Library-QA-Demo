<?php

include "globalFunctions.php";

$db = Factory::$database;

$result = $db->query("select * from reader_types");

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $output["readerTypes"][] = $row;
}
echo json_encode($output);
