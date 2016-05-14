<?php

include "globalFunctions.php";

$db = Factory::$database;

$result = $db->query("select * from allowed_books_num");
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $output["booksNum"][] = $row["maxBooks"];
}
echo json_encode($output);
