<?php

include "globalFunctions.php";

login();

function login() {
    $param = new Param();
    $user = Factory::getUser();
    $success = false;
    if (!$user->authenticate() && $param->exist("username")) {
        $validator = new InputValidator();
        if (!$validator->validate($param)) {
            Factory::write($validator->errorLogger->getErrors());
            return;
        }
        $user->username = $param->get("username");
        $user->password = $param->get("password");
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
