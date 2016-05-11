<?php

include "globalFunctions.php";

login();

function login() {
    $param = Param::getArray();
    $user = Factory::makeUser();
    $success = false;
    if (!$user->authenticate() && isset($param["username"])) {
        $validator = Factory::makeInputValidator();
        if (!$validator->validate($param)) {
            $validator->errorLogger->printJSON();
            return;
        }
        $user->username = $param["username"];
        $user->password = $param["password"];
        if ($user->login($validator->errorLogger)) {
            $success = true;
        } else {
            $validator->errorLogger->printJSON();
        }
    } else {
        if ($user->fetchLoggedUser()) {
            $success = true;
        } else {
            $output["success"] = false;
            echo json_encode($output);
        }
    }

    if ($success) {
        $output["success"] = true;
        $output["username"] = $user->username;
        $output["name"] = $user->name;
        echo json_encode($output);
    }
}

?>