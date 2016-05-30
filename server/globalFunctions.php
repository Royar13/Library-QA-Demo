<?php

include "../vendor/autoload.php";

header("Content-Type: application/json; charset=UTF-8");
//session_set_cookie_params(3600 * 24 * 300);
session_start();

Factory::$database = new Database(array("servername" => "localhost", "username" => "root", "password" => "", "dbname" => "library2"));
Factory::$writer = new JSONWriter();
