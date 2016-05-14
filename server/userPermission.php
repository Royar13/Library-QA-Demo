<?php

$user = Factory::getUser();
$user->fetchLoggedUser();
if (!$user->authenticate()) {
    die();
}