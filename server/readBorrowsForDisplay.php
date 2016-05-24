<?php

include "globalFunctions.php";
include "userPermission.php";

$borrow = Factory::makeBookBorrow();
$borrow->readerId = (new Param())->get("readerId");
$result = $borrow->readBorrowsForDisplay();
$output["borrows"] = array();
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $output["borrows"][] = $row;
}
Factory::write($output);
