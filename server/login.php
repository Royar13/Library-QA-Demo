<?php

include "globalFunctions.php";

$user = Factory::makeUser();
$validator = Factory::makeFormValidator("login");
if ($user->login($validator)) {
    $dataArr = array(
        "success" => true,
        "username" => $user->username,
        "name" => $user->name
    );
} else {
    $dataArr = array(
        "success" => false,
        "errors" => $validator->getErrors()
    );
}
echo json_encode($dataArr);
?>