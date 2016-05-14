<?php

include "globalFunctions.php";
include "userPermission.php";

$reader = Factory::makeReader();
$result = $reader->readAll();
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $row["address"] = "";
    if (!empty($row["city"])) {
        $row["address"] = $row["street"] . ", " . $row["city"];
    }
    $output["readers"][] = $row;
}
Factory::write($output);
