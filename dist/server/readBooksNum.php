<?php

include "globalFunctions.php";
include "userPermission.php";

$db = Factory::$database;

$result = $db->query("select * from allowed_books_num");
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $output["booksNum"][] = $row["maxBooks"];
}
Factory::write($output);