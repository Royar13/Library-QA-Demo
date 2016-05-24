<?php

include "globalFunctions.php";
include "userPermission.php";

$db = Factory::$database;

$result = $db->query("select * from sections");

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $output["sections"][] = $row;
}
Factory::write($output);
