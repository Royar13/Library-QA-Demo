<?php

include "../vendor/autoload.php";

header("Content-Type: application/json; charset=UTF-8");
//session_set_cookie_params(3600 * 24 * 300);
session_start();

Factory::$database = new Database(array("servername" => "mysql.hostinger.co.il", "username" => "u990065029_roy", "password" => "H8ezDZt4b5", "dbname" => "u990065029_lib"));
Factory::$writer = new JSONWriter();
