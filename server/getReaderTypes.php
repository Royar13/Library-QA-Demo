<?php

include "globalFunctions.php";

$db = Factory::$database;

$result = $db->query("select * from reader_types");
while ($row = mysqli_fetch_assoc($result)) {
    $output["readerTypes"][] = $row;
}
echo json_encode($output);