<?php

include "globalFunctions.php";

$db = Factory::$database;

$result = $db->query("select * from allowed_books_num");
while ($row = mysqli_fetch_assoc($result)) {
    $output["booksNum"][] = $row["maxBooks"];
}
echo json_encode($output);