<?php

include "globalFunctions.php";
include "userPermission.php";

$reader = Factory::makeReader();
$reader->id = (new Param())->get("id");
$result = $reader->getBorrowedBooks();
$output["borrows"] = array();
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $output["borrows"][] = $row;
}
Factory::write($output);
