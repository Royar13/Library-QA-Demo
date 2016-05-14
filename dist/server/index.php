<?php

include "globalFunctions.php";

$p = new Param();
if (!isset($p->get("action")))
    die();
$action = $p->get("action");
if ($action == "login") {
    include "login.php";
} else {
    $user = Factory::makeUser();
    if (!$user->authenticate()) {
        die();
    }
}

