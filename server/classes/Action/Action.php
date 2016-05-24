<?php

abstract class Action {

    public $description;
    public $userId;

    abstract public function create();

    abstract public function read();
}
