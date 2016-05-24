
<?php

include "globalFunctions.php";
include "userPermission.php";

$book = Factory::makeBook();
$result = $book->readAllBorrowAPI();
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $output["books"][] = $row;
}
Factory::write($output);