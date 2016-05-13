<?php

include "globalFunctions.php";

$reader = Factory::makeReader();
$proxy = new ProxyReadReaders($reader);
$proxy->setUser(Factory::makeUser());
$result = $proxy->readAll();
while ($row = mysqli_fetch_assoc($result)) {
    $output["readers"][] = $row;
}
Factory::write($output);
