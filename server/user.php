<?php

function login() {
    $param = new Param();
    $user = Factory::getUser();

    $user->username = $param->get("username");
    $user->password = $param->get("password");
    if ($user->login()) {
        $output["success"] = true;
        $output["username"] = $user->username;
        $output["name"] = $user->name;
    } else {
        $output["success"] = false;
        $output["errors"]["general"][] = "פרטי התחברות שגויים";
    }
    Factory::write($output);
}

function fetchLoggedUser() {
    $param = new Param();
    $user = Factory::getUser();
    if ($user->fetchLoggedUser()) {
        $output["success"] = true;
        $output["username"] = $user->username;
        $output["name"] = $user->name;
    } else {
        $output["success"] = false;
    }
    Factory::write($output);
}

function disconnect() {
    $user = Factory::getUser();
    $user->disconnect();
}
