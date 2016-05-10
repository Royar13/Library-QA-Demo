<?php

include "globalFunctions.php";

$reader = Factory::makeReader();
$validator = Factory::makeFormValidator("reader");
if ($reader->create($validator)) {
    $dataArr = array(
        "success" => true
    );
} else {
    $dataArr = array(
        "success" => false,
        "errors" => $validator->getErrors()
    );
}
echo json_encode($dataArr);
?>