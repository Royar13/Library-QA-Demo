<?php

include "globalFunctions.php";

$user = Factory::makeUser();
if ($user->login()) {
    $dataArr = array(
        "success" => true,
        "username" => $user->username,
        "name" => $user->name
    );
} else {
    $dataArr = array(
        "success" => false,
        "errors" => $user->formValidator->outputErrors()
    );
}
echo json_encode($dataArr);
?>