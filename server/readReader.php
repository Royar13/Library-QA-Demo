<?php

include "globalFunctions.php";
include "userPermission.php";

$reader = Factory::makeReader();
$param = new Param();
$reader->id = $param->get("id");
$reader->readOne();

$output["id"] = $reader->id;
$output["name"] = $reader->name;
$output["city"] = $reader->city;
$output["street"] = $reader->street;
$output["readerType"] = $reader->readerType;
$output["maxBooks"] = $reader->maxBooks;
$output["joinDate"] = $reader->joinDate;
$output["payments"] = $reader->payments;

Factory::write($output);

