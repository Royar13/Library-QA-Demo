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
            Factory::write($validator->errorLogger->getErrors());
            return;
        }
        $user->username = $param["username"];
        $user->password = $param["password"];
        if ($user->login($validator->errorLogger)) {
            $success = true;
        } else {
            Factory::write($validator->errorLogger->getErrors());
        }
    } else {
        if ($user->fetchLoggedUser()) {
            $success = true;
        } else {
            $output["success"] = false;
            Factory::write($output);
        }
    }

    if ($success) {
        $output["success"] = true;
        $output["username"] = $user->username;
        $output["name"] = $user->name;
        Factory::write($output);
    }
}